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

        $data = [
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

        $data = removeEmptyValues($data);

        return $data;
    }

    private function createDosageArray($dosageAttribute): array
    {
        $dosage = [];

        if (is_array($dosageAttribute) || is_object($dosageAttribute)) {
            foreach ($dosageAttribute as $d) {
                $dosage[] = [
                    'sequence' => $d->sequence,
                    'text' => $d->text,
                    'additionalInstruction' => $this->createCodeableConceptArray($d->additionalInstruction),
                    'patientInstruction' => $d->patient_instruction,
                    'timing' => [
                        'event' => $d->timing_event,
                        'repeat' => $d->timing_repeat,
                        'code' => [
                            'coding' => [
                                [
                                    'system' => $d->timing_system,
                                    'code' => $d->timing_code,
                                    'display' => $d->timing_display
                                ]
                            ]
                        ]
                    ],
                    'site' => [
                        'coding' => [
                            [
                                'system' => $d->site_system,
                                'code' => $d->site_code,
                                'display' => $d->site_display
                            ]
                        ]
                    ],
                    'route' => [
                        'coding' => [
                            [
                                'system' => $d->route_system,
                                'code' => $d->route_code,
                                'display' => $d->route_display
                            ]
                        ]
                    ],
                    'method' => [
                        'coding' => [
                            [
                                'system' => $d->method_system,
                                'code' => $d->method_code,
                                'display' => $d->method_display
                            ]
                        ]
                    ],
                    'doseAndRate' => $this->createDoseRateArray($d->doseRate),
                    'maxDosePerPeriod' => [
                        'numerator' => [
                            'value' => $d->max_dose_per_period_numerator_value,
                            'comparator' => $d->max_dose_per_period_numerator_comparator,
                            'unit' => $d->max_dose_per_period_numerator_unit,
                            'system' => $d->max_dose_per_period_numerator_system,
                            'code' => $d->max_dose_per_period_numerator_code,
                        ],
                        'denominator' => [
                            'value' => $d->max_dose_per_period_denominator_value,
                            'comparator' => $d->max_dose_per_period_denominator_comparator,
                            'unit' => $d->max_dose_per_period_denominator_unit,
                            'system' => $d->max_dose_per_period_denominator_system,
                            'code' => $d->max_dose_per_period_denominator_code,
                        ]
                    ],
                    'maxDosePerAdministration' => [
                        'value' => $d->max_dose_per_administration_value,
                        'unit' => $d->max_dose_per_administration_unit,
                        'system' => $d->max_dose_per_administration_system,
                        'code' => $d->max_dose_per_administration_code,
                    ],
                    'maxDosePerLifetime' => [
                        'value' => $d->max_dose_per_lifetime_value,
                        'unit' => $d->max_dose_per_lifetime_unit,
                        'system' => $d->max_dose_per_lifetime_system,
                        'code' => $d->max_dose_per_lifetime_code,
                    ]
                ];
            }
        }

        return $dosage;
    }

    private function createDoseRateArray($doseRateAttribute): array
    {
        $doseRate = [];

        if (is_array($doseRateAttribute) || is_object($doseRateAttribute)) {
            foreach ($doseRateAttribute as $dr) {
                $doseRate[] = merge_array(
                    [
                        'type' => [
                            'coding' => [
                                [
                                    'system' => $dr->system,
                                    'code' => $dr->code,
                                    'display' => $dr->display
                                ]
                            ]
                        ],
                    ],
                    $dr->dose,
                    $dr->rate
                );
            }
        }

        return $doseRate;
    }
}
