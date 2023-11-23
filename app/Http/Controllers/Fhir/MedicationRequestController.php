<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationRequestRequest;
use App\Http\Resources\MedicationRequestResource;
use App\Services\FhirService;

class MedicationRequestController extends Controller
{
    /**
     * Store a new medication request.
     *
     * @param MedicationRequestRequest $request The request object containing the medication request data.
     * @param FhirService $fhirService The FHIR service used to insert the data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the inserted medication request.
     */
    public function store(MedicationRequestRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('MedicationRequest');
            $medicationRequest = $resource->medicationRequest()->create($body['medicationRequest']);
            $this->createChildModels($medicationRequest, $body, ['identifier', 'category', 'reason', 'basedOn', 'insurance', 'note']);
            $this->createNestedInstances($medicationRequest, 'dosage', $body, ['additionalInstruction', 'doseRate']);
            $this->createResourceContent(MedicationRequestResource::class, $resource);
            return response()->json($resource->medicationRequest->first(), 201);
        });
    }


    /**
     * Update a medication request.
     *
     * @param MedicationRequestRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(MedicationRequestRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $medicationRequest = $resource->medicationRequest()->first();
            $medicationRequest->update($body['medicationRequest']);
            $requestId = $medicationRequest->id;
            $this->updateChildModels($medicationRequest, $body, ['identifier', 'category', 'reason', 'basedOn', 'insurance', 'note'], 'med_req_id', $requestId);
            $this->updateNestedInstances($medicationRequest, 'dosage', $body, 'med_req_id', $requestId, ['additionalInstruction', 'doseRate'], 'med_req_id');
            $this->createResourceContent(MedicationRequestResource::class, $resource);

            return response()->json($resource->medicationRequest->first(), 200);
        });
    }
}
