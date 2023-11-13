<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\GeneralPractitioner;
use App\Models\Patient;
use App\Models\PatientAddress;
use App\Models\PatientContact;
use App\Models\PatientContactTelecom;
use App\Models\PatientIdentifier;
use App\Models\PatientTelecom;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class PatientController extends Controller
{
    public function postPatient(PatientRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('Patient');

            $patient = Patient::create(array_merge($resourceKey, $body['patient']));

            $patientKey = ['patient_id' => $patient->id];

            $this->createInstances(PatientIdentifier::class, $patientKey, $body, 'identifier');
            $this->createInstances(PatientTelecom::class, $patientKey, $body, 'telecom');
            $this->createInstances(PatientAddress::class, $patientKey, $body, 'address');
            $this->createInstances(GeneralPractitioner::class, $patientKey, $body, 'general_practitioner');
            if (isset($body['contact']) && !empty($body['contact'])) {
                $this->createInstances(PatientContact::class, $patientKey, $body['contact'], 'contact_data', [
                    [
                        'model' => PatientContactTelecom::class,
                        'key' => 'contact_id',
                        'bodyKey' => 'telecom'
                    ]
                ]);
            }

            $this->createResourceContent(PatientResource::class, $resource);

            return response()->json($resource->patient->first(), 201);
        });
    }
}
