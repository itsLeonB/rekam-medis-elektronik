<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationDispenseRequest;
use App\Http\Resources\MedicationDispenseResource;
use App\Models\MedicationDispense;
use App\Models\MedicationDispenseAuthorizingPrescription;
use App\Models\MedicationDispenseDosageInstruction;
use App\Models\MedicationDispenseDosageInstructionAdditionalInstruction;
use App\Models\MedicationDispenseDosageInstructionDoseRate;
use App\Models\MedicationDispenseIdentifier;
use App\Models\MedicationDispensePartOf;
use App\Models\MedicationDispensePerformer;
use App\Models\MedicationDispenseSubstitution;
use App\Models\MedicationDispenseSubstitutionReason;
use App\Models\MedicationDispenseSubstitutionResponsibleParty;
use App\Services\FhirService;

class MedicationDispenseController extends Controller
{
    public function postMedicationDispense(MedicationDispenseRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('MedicationDispense');

            $medicationDispense = MedicationDispense::create(array_merge($resourceKey, $body['medication_dispense']));

            $medicationDispenseKey = ['dispense_id' => $medicationDispense->id];

            $this->createInstances(MedicationDispenseIdentifier::class, $medicationDispenseKey, $body, 'identifier');
            $this->createInstances(MedicationDispensePartOf::class, $medicationDispenseKey, $body, 'part_of');
            $this->createInstances(MedicationDispensePerformer::class, $medicationDispenseKey, $body, 'performer');
            $this->createInstances(MedicationDispenseAuthorizingPrescription::class, $medicationDispenseKey, $body, 'authorizing_prescription');

            if (is_array($body['dosage']) || is_object($body['dosage'])) {
                $this->createNestedInstances(MedicationDispenseDosageInstruction::class, $medicationDispenseKey, $body, 'dosage', [
                    [
                        'model' => MedicationDispenseDosageInstructionAdditionalInstruction::class,
                        'key' => 'med_disp_dose_id',
                        'bodyKey' => 'additional_instruction'
                    ],
                    [
                        'model' => MedicationDispenseDosageInstructionDoseRate::class,
                        'key' => 'med_disp_dose_id',
                        'bodyKey' => 'dose_rate'
                    ]
                ]);
            }

            if (is_array($body['substitution']) || is_object($body['substitution'])) {
                $this->createNestedInstances(MedicationDispenseSubstitution::class, $medicationDispenseKey, $body, 'substitution', [
                    [
                        'model' => MedicationDispenseSubstitutionReason::class,
                        'key' => 'med_disp_subs_id',
                        'bodyKey' => 'reason'
                    ],
                    [
                        'model' => MedicationDispenseSubstitutionResponsibleParty::class,
                        'key' => 'med_disp_subs_id',
                        'bodyKey' => 'responsible_party'
                    ]
                ]);
            }

            $this->createResourceContent(MedicationDispenseResource::class, $resource);

            return response()->json($resource->medicationDispense->first(), 201);
        });
    }
}
