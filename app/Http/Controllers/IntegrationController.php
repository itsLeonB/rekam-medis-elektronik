<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FhirRequest;
use App\Models\FailedApiRequest;
use App\Models\Fhir\Resource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $lastUpdated = Carbon::parse($satusehatResponseBody['meta']['lastUpdated'])->setTimezone('Asia/Jakarta');

        if ($lastUpdated->gt($resourceUpdatedAt)) {
            $request = Request::create(route($resourceType . '.update', ['satusehat_id' => $resourceId]), 'PUT', $satusehatResponseBody);
            $response = app()->handle($request);
            return $response;
        } else {
            return $satusehatResponseBody;
        }
    }

    public function show($resourceType, $resourceId)
    {
        $satusehatResponse = $this->satusehatController->show($resourceType, $resourceId);
        if ($satusehatResponse->getStatusCode() === 200) {
            $satusehatResponseBody = json_decode($satusehatResponse->getBody()->getContents(), true);

            if ($this->checkIfResourceExistsInLocal($resourceType, $resourceId)) {
                return $this->updateResourceIfNewer($resourceType, $resourceId, $satusehatResponseBody);
            } else {
                $resourceType = strtolower($resourceType);
                $request = Request::create(route($resourceType . '.store'), 'POST', $satusehatResponseBody);
                return app()->handle($request);
            }
        } else {
            Log::error($satusehatResponse->getContent());

            $resourceType = strtolower($resourceType);
            $request = Request::create(route($resourceType . '.show', ['satusehat_id' => $resourceId]), 'GET');
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
        $data = $request->all();

        if (strtolower($res_type) != strtolower($data['resourceType'])) {
            Log::error('Resource type mismatch', $res_type, $data['resourceType']);
            return response()->json(['error' => 'Resource type mismatch'], 400);
        } else {
            $satusehatRequest = Request::create(route('satusehat.store', ['res_type' => $res_type]), 'POST', $data);

            $satusehatResponse = retry(3, function () use ($satusehatRequest) {
                return app()->handle($satusehatRequest);
            }, 100);

            $statusCode = $satusehatResponse->getStatusCode();

            if ($statusCode === 201) {
                $data = json_decode($satusehatResponse->getContent(), true);
                $res_type = strtolower($res_type);
                $storeRequest = Request::create(route($res_type . '.store'), 'POST', $data);
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
                    'method' => 'POST',
                    'data' => $request->all(),
                ]);

                return $satusehatResponse;
            }
        }
    }

    public function update(FhirRequest $request, $res_type, $satusehat_id)
    {
        $data = $request->all();

        if (strtolower($res_type) != strtolower($data['resourceType'])) {
            Log::error('Resource type mismatch', $res_type, $data['resourceType']);
            return response()->json(['error' => 'Resource type mismatch'], 400);
        } else {
            $satusehatRequest = Request::create(route('satusehat.update', ['res_type' => $res_type, 'res_id' => $satusehat_id]), 'PUT', $data);

            $satusehatResponse = retry(3, function () use ($satusehatRequest) {
                return app()->handle($satusehatRequest);
            }, 100);

            $statusCode = $satusehatResponse->getStatusCode();

            if ($statusCode === 200) {
                $data = json_decode($satusehatResponse->getContent(), true);
                $res_type = strtolower($res_type);
                $storeRequest = Request::create(route($res_type . '.update', ['satusehat_id' => $satusehat_id]), 'PUT', $data);
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
