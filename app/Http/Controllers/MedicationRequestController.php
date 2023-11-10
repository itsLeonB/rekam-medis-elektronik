<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationRequestRequest;
use App\Http\Resources\MedicationRequestResource;
use App\Models\MedicationRequest;
use App\Models\MedicationRequestBasedOn;
use App\Models\MedicationRequestCategory;
use App\Models\MedicationRequestDosage;
use App\Models\MedicationRequestDosageAdditionalInstruction;
use App\Models\MedicationRequestDosageDoseRate;
use App\Models\MedicationRequestIdentifier;
use App\Models\MedicationRequestInsurance;
use App\Models\MedicationRequestNote;
use App\Models\MedicationRequestReason;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class MedicationRequestController extends Controller
{
    public function postMedicationRequest(MedicationRequestRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('MedicationRequest');

            $medicationRequest = MedicationRequest::create(array_merge($resourceKey, $body['medication_request']));

            $medicationRequestKey = ['medication_id' => $medicationRequest->id];

            $this->createInstances(MedicationRequestIdentifier::class, $medicationRequestKey, $body, 'identifier');
            $this->createInstances(MedicationRequestCategory::class, $medicationRequestKey, $body, 'category');
            $this->createInstances(MedicationRequestReason::class, $medicationRequestKey, $body, 'reason');
            $this->createInstances(MedicationRequestBasedOn::class, $medicationRequestKey, $body, 'based_on');
            $this->createInstances(MedicationRequestInsurance::class, $medicationRequestKey, $body, 'insurance');
            $this->createInstances(MedicationRequestNote::class, $medicationRequestKey, $body, 'note');

            if (isset($body['dosage']) && !empty($body['dosage'])) {
                $this->createInstances(MedicationRequestDosage::class, $medicationRequestKey, $body['dosage'], 'dosage_data', [
                    [
                        'model' => MedicationRequestDosageAdditionalInstruction::class,
                        'key' => 'med_req_dosage_id',
                        'bodyKey' => 'additional_instruction'
                    ],
                    [
                        'model' => MedicationRequestDosageDoseRate::class,
                        'key' => 'med_req_dosage_id',
                        'bodyKey' => 'dose_rate'
                    ]
                ]);
            }

            $this->createResourceContent(MedicationRequestResource::class, $resource);

            return response()->json($resource->medicationRequest->first(), 201);
        });
    }
}
