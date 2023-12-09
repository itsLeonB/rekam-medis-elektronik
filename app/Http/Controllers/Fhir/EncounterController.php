<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\EncounterRequest;
use App\Http\Resources\EncounterResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class EncounterController extends Controller
{
    const RESOURCE_TYPE = 'Encounter';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new EncounterResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(EncounterRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        // return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $encounter = $resource->encounter()->create($body['encounter']);
            $this->createChildModels($encounter, $body, ['identifier', 'statusHistory', 'classHistory', 'participant', 'diagnosis', 'location']);
            $this->createResourceContent(EncounterResource::class, $resource);
            return response()->json($encounter, 201);
        // });
    }


    public function update(EncounterRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $encounter = $resource->encounter()->first();
            $encounter->update($body['encounter']);
            $this->updateChildModels($encounter, $body, ['identifier', 'statusHistory', 'classHistory', 'participant', 'diagnosis', 'location']);
            $this->createResourceContent(EncounterResource::class, $resource);
            return response()->json($encounter, 200);
        });
    }
}
