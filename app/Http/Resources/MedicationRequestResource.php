<?php

namespace App\Http\Resources;

use App\Models\Medication;
use App\Models\MedicationRequest;
use App\Models\ValueSetProcedurePerformerType;
use Illuminate\Http\Request;

class MedicationRequestResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medicationRequest = $this->getData('medicationrequest');

        $data = $this->resourceStructure($medicationRequest);

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($medicationRequest): array
    {
        return $data = [
            'resourceType' => 'MedicationRequest',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($medicationRequest->identifier),
            'status' => $medicationRequest->status,
            'statusReason' => [
                'coding' => [
                    [
                        'system' => $medicationRequest->status_reason ? MedicationRequest::STATUS_REASON_SYSTEM : null,
                        'code' => $medicationRequest->status_reason,
                        'display' => MedicationRequest::STATUS_REASON_DISPLAY[$medicationRequest->status_reason] ?? null,
                    ]
                ]
            ],
            'intent' => $medicationRequest->intent,
            'category' => $this->createCodeableConceptArray($medicationRequest->category),
            'priority' => $medicationRequest->priority,
            'doNotPerform' => $medicationRequest->do_not_perform,
            'reportedBoolean' => $medicationRequest->reported,
            'medicationReference' => [
                'reference' => $medicationRequest->medication
            ],
            'subject' => [
                'reference' => $medicationRequest->subject
            ],
            'encounter' => [
                'reference' => $medicationRequest->encounter
            ],
            'authoredOn' => $this->parseDateFhir($medicationRequest->authored_on),
            'requester' => [
                'reference' => $medicationRequest->requester
            ],
            'performer' => [
                'reference' => $medicationRequest->performer
            ],
            'performerType' => [
                'coding' => [
                    [
                        'system' => $medicationRequest->performer_type ? ValueSetProcedurePerformerType::SYSTEM : null,
                        'code' => $medicationRequest->performer_type,
                        'display' => ValueSetProcedurePerformerType::where('code', $medicationRequest->performer_type)->first()->display ?? null,
                    ]
                ]
            ],
            'recorder' => [
                'reference' => $medicationRequest->recorder
            ],
            'reasonCode' => $this->createCodeableConceptArray($medicationRequest->reason),
            'reasonReference' => $this->createReferenceArray($medicationRequest->reason),
            'basedOn' => $this->createReferenceArray($medicationRequest->basedOn),
            'courseOfTherapyType' => [
                'coding' => [
                    [
                        'system' => MedicationRequest::COURSE_OF_THERAPY_SYSTEM,
                        'code' => $medicationRequest->course_of_therapy,
                        'display' => MedicationRequest::COURSE_OF_THERAPY_DISPLAY[$medicationRequest->course_of_therapy] ?? null
                    ]
                ]
            ],
            'insurance' => $this->createReferenceArray($medicationRequest->insurance),
            'note' => $this->createAnnotationArray($medicationRequest->note),
            'dosageInstruction' => $this->createDosageArray($medicationRequest->dosage),
            'dispenseRequest' => [
                'dispenseInterval' => [
                    'value' => $medicationRequest->dispense_interval_value,
                    'comparator' => $medicationRequest->dispense_interval_comparator,
                    'unit' => $medicationRequest->dispense_interval_unit,
                    'system' => $medicationRequest->dispense_interval_system,
                    'code' => $medicationRequest->dispense_interval_code
                ],
                'validityPeriod' => [
                    'start' => $medicationRequest->validity_period_start,
                    'end' => $medicationRequest->validity_period_end
                ],
                'numberOfRepeatsAllowed' => $medicationRequest->repeats_allowed,
                'quantity' => [
                    'value' => $medicationRequest->quantity_value,
                    'unit' => $medicationRequest->quantity_unit,
                    'system' => $medicationRequest->quantity_system,
                    'code' => $medicationRequest->quantity_code
                ],
                'expectedSupplyDuration' => [
                    'value' => $medicationRequest->supply_duration_value,
                    'comparator' => $medicationRequest->supply_duration_comparator,
                    'unit' => $medicationRequest->supply_duration_unit,
                    'system' => $medicationRequest->supply_duration_system,
                    'code' => $medicationRequest->supply_duration_code
                ],
                'performer' => [
                    'reference' => $medicationRequest->dispense_performer
                ]
            ],
            'substitution' => [
                $medicationRequest->substitution_allowed,
                'reason' => [
                    'coding' => [
                        [
                            'system' => $medicationRequest->substitution_reason ? MedicationRequest::SUBSTITUTION_REASON_SYSTEM : null,
                            'code' => $medicationRequest->substitution_reason,
                            'display' => MedicationRequest::SUBSTITUTION_REASON_DISPLAY[$medicationRequest->substitution_reason] ?? null
                        ]
                    ]
                ]
            ]
        ];
    }
}
