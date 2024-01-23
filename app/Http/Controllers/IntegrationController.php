<?php

namespace App\Http\Controllers;

use App\Fhir\Processor;
use App\Http\Controllers\Controller;
use App\Http\Requests\FhirRequest;
use App\Models\FailedApiRequest;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    public SatusehatController $satusehatController;

    public function __construct(SatusehatController $satusehatController)
    {
        $this->satusehatController = $satusehatController;
    }

    public function checkIfResourceExistsInLocal($resourceType, $resourceId)
    {
        return Resource::where([
            ['res_type', $resourceType],
            ['satusehat_id', $resourceId],
        ])->exists();
    }

    public function updateResourceIfNewer($resourceType, $resourceId, $satusehatResponseBody)
    {
        $resourceType = strtolower($resourceType);

        $resourceUpdatedAt = Resource::where([
            ['res_type', $satusehatResponseBody['resourceType']],
            ['satusehat_id', $satusehatResponseBody['id']],
        ])->first()->updated_at;
        $lastUpdated = Carbon::parse($satusehatResponseBody['meta']['lastUpdated'])->setTimezone(config('app.timezone'));

        if ($lastUpdated->gt($resourceUpdatedAt)) {
            $request = Request::create(route('local.' . $resourceType . '.update', ['satusehat_id' => $resourceId]), 'PUT', $satusehatResponseBody);
            $response = app()->handle($request);
            return $response;
        } else {
            return $satusehatResponseBody;
        }
    }

    public function updateOrCreate($resourceType, $resourceId, $resource)
    {
        $resourceType = strtolower($resourceType);

        if ($this->checkIfResourceExistsInLocal($resourceType, $resourceId)) {
            return $this->updateResourceIfNewer($resourceType, $resourceId, $resource);
        } else {
            $request = Request::create(route('local.' . $resourceType . '.store'), 'POST', $resource);
            return app()->handle($request);
        }
    }

    public function show($resourceType, $resourceId)
    {
        $satusehatResponse = $this->satusehatController->show($resourceType, $resourceId);
        if ($satusehatResponse->getStatusCode() === 200) {
            $satusehatResponseBody = json_decode($satusehatResponse->getBody()->getContents(), true);

            return $this->updateOrCreate($resourceType, $resourceId, $satusehatResponseBody);
        } else {
            Log::error($satusehatResponse->getContent());

            $resourceType = strtolower($resourceType);
            $request = Request::create(route('local.' . $resourceType . '.show', ['satusehat_id' => $resourceId]), 'GET');
            $request->headers->add(['X-CSRF-TOKEN' => csrf_token()]);
            $localResponse = app()->handle($request);

            if ($localResponse->getStatusCode() === 200) {
                return $localResponse;
            } else {
                Log::error($localResponse->getContent());
                return $localResponse;
            }
        }
    }

    public function store(FhirRequest $request, $res_type)
    {
        DB::beginTransaction();

        try {
            $data = $request->all();

            if (strtolower($res_type) != strtolower($data['resourceType'])) {
                Log::error('Resource type mismatch', $res_type, $data['resourceType']);
                return response()->json(['error' => 'Resource type mismatch'], 400);
            }

            $resource = Resource::create(['res_type' => $res_type]);

            $processor = new Processor();

            $data = $processor->generateResource($data, $res_type);
            $savedData = $processor->saveResource($resource, $data, $res_type);

            $noIdentifiers = ['Organization', 'Location', 'Practitioner'];

            if (!in_array($res_type, $noIdentifiers)) {
                $resType = strtolower($res_type);

                if ($resType == 'patient') {
                    $rmeSystem = config('app.identifier_systems.patient.rekam-medis');

                    $existingIdentifier = $savedData->identifier()
                        ->where('system', $rmeSystem)
                        ->first();

                    if (!$existingIdentifier) {
                        $identifier = new Identifier();
                        $identifier->system = $rmeSystem;
                        $identifier->use = 'official';
                        $highestValue = Identifier::where('system', $rmeSystem)->max('value');
                        $nextValue = $highestValue + 1;
                        $formattedValue = str_pad($nextValue, 6, '0', STR_PAD_LEFT);
                        $identifier->value = $formattedValue;
                        $savedData->identifier()->save($identifier);
                    }
                } else {
                    $existingIdentifier = $savedData->identifier()
                        ->where('system', config('app.identifier_systems.' . $resType))
                        ->first();

                    if (!$existingIdentifier) {
                        $identifier = new Identifier();
                        $identifier->system = config('app.identifier_systems.' . $resType);
                        $identifier->use = 'official';
                        $identifier->value = Str::uuid();
                        $savedData->identifier()->save($identifier);
                    }
                }
            }

            $savedData->refresh();
            $resText = $processor->makeResourceText($savedData, $res_type);
            $resText = json_decode(json_encode($resText), true);

            $satusehatRequest = Request::create(route('satusehat.resource.store', ['res_type' => $res_type]), 'POST', $resText);

            $satusehatResponse = retry(3, function () use ($satusehatRequest) {
                return app()->handle($satusehatRequest);
            }, 100);

            $statusCode = $satusehatResponse->getStatusCode();
            $remoteText = $satusehatResponse->getContent();
            $remoteData = json_decode($remoteText, true);
            $satusehatId = data_get($remoteData, 'id');

            if ($statusCode === 201) {
                $resource->satusehat_id = $satusehatId;

                $resource->content()->create(['res_text' => $remoteText]);

                $resource->save();

                DB::commit();

                return $satusehatResponse;
            } elseif ((400 <= $statusCode) && ($statusCode < 500)) {
                DB::rollBack();
                Log::error($satusehatResponse->getContent());
                return response()->json([
                    'error' => 'Client error',
                    'content' => $satusehatResponse,
                    'data' => $resText
                ], $satusehatResponse->getStatusCode());
            } else {
                DB::rollBack();
                Log::error('SATUSEHAT server error: ' . $satusehatResponse->getContent());

                FailedApiRequest::create([
                    'method' => 'POST',
                    'data' => $request->all(),
                ]);

                return $satusehatResponse;
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(FhirRequest $request, $res_type, $satusehat_id)
    {
        $data = $request->all();

        if (strtolower($res_type) != strtolower($data['resourceType'])) {
            Log::error('Resource type mismatch', $res_type, $data['resourceType']);
            return response()->json(['error' => 'Resource type mismatch'], 400);
        } else {
            $satusehatRequest = Request::create(route('satusehat.resource.update', ['res_type' => $res_type, 'res_id' => $satusehat_id]), 'PUT', $data);

            $satusehatResponse = retry(3, function () use ($satusehatRequest) {
                return app()->handle($satusehatRequest);
            }, 100);

            $statusCode = $satusehatResponse->getStatusCode();

            if ($statusCode === 200) {
                $data = json_decode($satusehatResponse->getContent(), true);
                $res_type = strtolower($res_type);
                $storeRequest = Request::create(route('local.' . $res_type . '.update', ['satusehat_id' => $satusehat_id]), 'PUT', $data);
                app()->handle($storeRequest);
                return $satusehatResponse;
            } elseif ((400 <= $statusCode) && ($statusCode < 500)) {
                Log::error($satusehatResponse->getContent());
                return response()->json([
                    'error' => 'Client error',
                    'data' => $request->all()
                ], $satusehatResponse->getStatusCode());
            } else {
                Log::error('SATUSEHAT server error: ' . $satusehatResponse->getContent());

                FailedApiRequest::create([
                    'method' => 'PUT',
                    'data' => $request->all(),
                ]);

                return $satusehatResponse;
            }
        }
    }
}
