<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EncounterRequest;
use App\Http\Resources\EncounterResource;
use App\Services\FhirService;

class EncounterController extends Controller
{
    /**
     * Store a new encounter.
     *
     * @param EncounterRequest $request The encounter request object.
     * @param FhirService $fhirService The FHIR service object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the newly created encounter.
     */
    public function store(EncounterRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Encounter');
            $encounter = $resource->encounter()->create($body['encounter']);
            $this->createChildModels($encounter, $body, ['identifier', 'statusHistory', 'classHistory', 'participant', 'reason', 'diagnosis']);
            $this->createNestedInstances($encounter, 'hospitalization', $body, ['diet', 'specialArrangement']);
            $this->createResourceContent(EncounterResource::class, $resource);
            return response()->json($resource->encounter->first(), 201);
        });
    }


    /**
     * Update an encounter resource.
     *
     * @param EncounterRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(EncounterRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $encounter = $resource->encounter()->first();
            $encounter->update($body['encounter']);
            $encounterId = $encounter->id;
            $this->updateChildModels($encounter, $body, ['identifier', 'statusHistory', 'classHistory', 'participant', 'reason', 'diagnosis'], 'encounter_id', $encounterId);
            $this->updateNestedInstances($encounter, 'hospitalization', $body, 'encounter_id', $encounterId, ['diet', 'specialArrangement'], 'enc_hosp_id');
            $this->createResourceContent(EncounterResource::class, $resource);
            return response()->json($resource->encounter->first(), 200);
        });
    }
}
