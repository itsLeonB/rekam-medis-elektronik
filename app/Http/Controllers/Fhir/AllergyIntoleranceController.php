<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\FhirController;
use App\Http\Controllers\SatusehatController;
use App\Http\Requests\Fhir\AllergyIntoleranceRequest;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AllergyIntoleranceController extends FhirController
{
    const RESOURCE_TYPE = 'AllergyIntolerance';

    public function show($res_id)
    {
        $satusehat = new SatusehatController();

        $response = $satusehat->get(self::RESOURCE_TYPE, $res_id);

        if ($response->getStatusCode() == 200) {

            $localRes = Resource::where([
                ['res_type', self::RESOURCE_TYPE],
                ['id', $res_id]
            ])->value('updated_at');

            if ($response['meta']['lastUpdated'] > $localRes) {
                return response()->json(['error' => 'Data tidak ditemukan.'], 404);
            }

            return $response;
        }
        try {
            return response()
                ->json(new AllergyIntoleranceResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $allergyIntolerance = $resource->allergyIntolerance()->create($body['allergyIntolerance']);
            $this->createChildModels($allergyIntolerance, $body, ['identifier', 'note']);
            $this->createNestedInstances($allergyIntolerance, 'reaction', $body, ['note']);
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json($allergyIntolerance, 201);
        });
    }


    public function update(AllergyIntoleranceRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $allergyIntolerance = $resource->allergyIntolerance()->first();
            $allergyIntolerance->update($body['allergyIntolerance']);
            $this->updateChildModels($allergyIntolerance, $body, ['identifier', 'note']);
            $this->updateNestedInstances($allergyIntolerance, 'reaction', $body, ['note']);
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json($allergyIntolerance, 200);
        });
    }
}
