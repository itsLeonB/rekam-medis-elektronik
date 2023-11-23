<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationRequest;
use App\Http\Resources\MedicationResource;
use App\Services\FhirService;

class MedicationController extends Controller
{
    /**
     * Store a new medication record.
     *
     * @param MedicationRequest $request The request object containing the medication data.
     * @param FhirService $fhirService The FHIR service used to insert the data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the newly created medication resource.
     */
    public function store(MedicationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource= $this->createResource('Medication');
            $medication = $resource->medication()->create($body['medication']);
            $this->createChildModels($medication, $body, ['identifier', 'ingredient']);
            $this->createResourceContent(MedicationResource::class, $resource);
            return response()->json($resource->medication->first(), 201);
        });
    }


    /**
     * Update a medication resource.
     *
     * @param MedicationRequest $request The request object.
     * @param int $res_id The ID of the resource.
     * @param FhirService $fhirService The FhirService instance.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated medication resource.
     */
    public function update(MedicationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $medication = $resource->medication()->first();
            $medication->update($body['medication']);
            $medicationId = $medication->id;
            $this->updateChildModels($medication, $body, ['identifier', 'ingredient'], 'medication_id', $medicationId);
            $this->createResourceContent(MedicationResource::class, $resource);
            return response()->json($resource->medication->first(), 200);
        });
    }
}
