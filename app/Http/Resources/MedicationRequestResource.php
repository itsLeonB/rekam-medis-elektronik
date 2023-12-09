<?php

namespace App\Http\Resources;

use App\Models\Fhir\MedicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicationRequestResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medicationRequest = $this->getData('medicationRequest');

        $data = $this->resourceStructure($medicationRequest);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($medicationRequest): array
    {
        return [
            'resourceType' => 'MedicationRequest',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($medicationRequest->identifier),
            'status' => $medicationRequest->status,
            'statusReason' => [
                'coding' => [
                    [
                        'system' => $medicationRequest->status_reason ? MedicationRequest::STATUS_REASON['binding']['valueset']['system'] ?? null : null,
                        'code' => $medicationRequest->status_reason,
                        'display' => $medicationRequest->status_reason ? MedicationRequest::STATUS_REASON['binding']['valueset']['display'][$medicationRequest->status_reason] ?? null : null,
                    ]
                ]
            ],
            'intent' => $medicationRequest->intent,
            'category' => $this->createCategoryArray($medicationRequest->category),
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
            'supportingInformation' => $this->createReferenceArray($medicationRequest->supporting_information),
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
                        'system' => $medicationRequest->performer_type ? MedicationRequest::PERFORMER_TYPE['binding']['valueset']['system'] : null,
                        'code' => $medicationRequest->performer_type,
                        'display' => $medicationRequest->performer_type ? DB::table(MedicationRequest::PERFORMER_TYPE['binding']['valueset']['table'])
                            ->select('display')
                            ->where('code', $medicationRequest->performer_type)
                            ->first()->display ?? null : null
                    ]
                ]
            ],
            'recorder' => [
                'reference' => $medicationRequest->recorder
            ],
            'reasonCode' => $this->createReasonCodeArray($medicationRequest->reason_code),
            'reasonReference' => $this->createReferenceArray($medicationRequest->reason_reference),
            'basedOn' => $this->createReferenceArray($medicationRequest->based_on),
            'courseOfTherapyType' => [
                'coding' => [
                    [
                        'system' => $medicationRequest->course_of_therapy ? MedicationRequest::COURSE_OF_THERAPY_TYPE['binding']['valueset']['system'] : null,
                        'code' => $medicationRequest->course_of_therapy,
                        'display' => $medicationRequest->course_of_therapy ? MedicationRequest::COURSE_OF_THERAPY_TYPE['binding']['valueset']['display'][$medicationRequest->course_of_therapy] ?? null : null
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
                    'start' => $this->parseDateFhir($medicationRequest->validity_period_start),
                    'end' => $this->parseDateFhir($medicationRequest->validity_period_end)
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
                            'system' => $medicationRequest->substitution_reason ? MedicationRequest::SUBSTITUTION_REASON['binding']['valueset']['system'] : null,
                            'code' => $medicationRequest->substitution_reason,
                            'display' => $medicationRequest->substitution_reason ? MedicationRequest::SUBSTITUTION_REASON['binding']['valueset']['display'][$medicationRequest->substitution_reason] ?? null : null
                        ]
                    ]
                ]
            ]
        ];
    }


    private function createReasonCodeArray($reasonCodes): array
    {
        $reasonCode = [];

        if (!empty($reasonCodes)) {
            foreach ($reasonCodes as $rc) {
                $reasonCode[] = [
                    'coding' => [
                        [
                            'system' => $rc ? MedicationRequest::REASON_CODE['binding']['valueset']['system'] : null,
                            'code' => $rc,
                            'display' => $rc ? DB::table(MedicationRequest::REASON_CODE['binding']['valueset']['table'])
                                ->select('display_en')
                                ->where('code', '=', $rc)
                                ->first()->display_en ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $reasonCode;
    }


    private function createCategoryArray($categories): array
    {
        $category = [];

        if (!empty($categories)) {
            foreach ($categories as $c) {
                $category[] = [
                    'coding' => [
                        [
                            'system' => $c ? MedicationRequest::CATEGORY['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? MedicationRequest::CATEGORY['binding']['valueset']['display'][$c] : null
                        ]
                    ]
                ];
            }
        }

        return $category;
    }
}
