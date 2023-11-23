<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Services\FhirService;

class PatientController extends Controller
{
    /**
     * Store a new patient record.
     *
     * @param PatientRequest $request The request object containing the patient data.
     * @param FhirService $fhirService The FHIR service used to insert the data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the newly created patient record.
     */
    public function store(PatientRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Patient');
            $patient = $resource->patient()->create($body['patient']);
            $this->createChildModels($patient, $body, ['identifier', 'telecom', 'address', 'generalPractitioner']);
            $this->createNestedInstances($patient, 'contact', $body, ['telecom']);
            $this->createResourceContent(PatientResource::class, $resource);
            return response()->json($resource->patient->first(), 201);
        });
    }


    /**
     * Update a patient record.
     *
     * @param PatientRequest $request The request object containing the patient data.
     * @param int $res_id The ID of the resource.
     * @param FhirService $fhirService The FhirService instance for inserting data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated patient data.
     */
    public function update(PatientRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $patient = $resource->patient()->first();
            $patient->update($body['patient']);
            $patientId = $patient->id;
            $this->updateChildModels($patient, $body, ['identifier', 'telecom', 'address', 'generalPractitioner'], 'patient_id', $patientId);
            $this->updateNestedInstances($patient, 'contact', $body, 'patient_id', $patientId, ['telecom'], 'contact_id');
            $this->createResourceContent(PatientResource::class, $resource);
            return response()->json($resource->patient->first(), 200);
        });
    }
}
