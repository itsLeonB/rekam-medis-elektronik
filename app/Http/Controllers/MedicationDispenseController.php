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

        // return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('MedicationDispense');

            $medicationDispense = MedicationDispense::create(array_merge($resourceKey, $body['medication_dispense']));

            $medicationDispenseKey = ['dispense_id' => $medicationDispense->id];

            $this->createInstances(MedicationDispenseIdentifier::class, $medicationDispenseKey, $body, 'identifier');
            $this->createInstances(MedicationDispensePartOf::class, $medicationDispenseKey, $body, 'part_of');
            $this->createInstances(MedicationDispensePerformer::class, $medicationDispenseKey, $body, 'performer');
            $this->createInstances(MedicationDispenseAuthorizingPrescription::class, $medicationDispenseKey, $body, 'authorizing_prescription');

            // if (is_array($body['dosage_instruction']) || is_object($body['dosage_instruction'])) {
                $this->createInstances(MedicationDispenseDosageInstruction::class, $medicationDispenseKey, $body['dosage_instruction'], 'dosage_data', [
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
            // }

            if (is_array($body['substitution']) || is_object($body['substitution'])) {
                $subs = MedicationDispenseSubstitution::create(array_merge($medicationDispenseKey, $body['substitution']['substitution_data']));
                $subsKey = ['med_disp_subs_id' => $subs->id];
                $this->createInstances(MedicationDispenseSubstitutionReason::class, $subsKey, $body['substitution'], 'reason');
                $this->createInstances(MedicationDispenseSubstitutionResponsibleParty::class, $subsKey, $body['substitution'], 'responsible_party');
            }

            $this->createResourceContent(MedicationDispenseResource::class, $resource);

            return response()->json($resource->medicationDispense->first(), 201);
        // });
    }
}
