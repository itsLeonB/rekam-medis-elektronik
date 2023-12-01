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
}
