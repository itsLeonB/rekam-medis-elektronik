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
        $body = json_decode($request->getContent(), true);
        $body = removeEmptyValues($body);

        return $fhirService->insertData(function () use ($body) {
            $resource = Resource::create([
                'res_type' => 'Patient',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

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

            $resourceData = new PatientResource($resource);
            $resourceText = json_encode($resourceData);

            ResourceContent::create([
                'resource_id' => $resource->id,
                'res_ver' => 1,
                'res_text' => $resourceText,
            ]);

            return response()->json($resource->patient->first(), 201);
        });
    }
}
