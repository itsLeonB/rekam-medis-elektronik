<?php

namespace App\Http\Resources;

use App\Models\MedicationDispense;
use App\Models\MedicationDispensePerformer;
use Illuminate\Http\Request;

class MedicationDispenseResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medicationDispense = $this->getData('medicationdispense');

        $data = $this->resourceStructure($medicationDispense);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($data): array
    {
        return [
            'resourceType' => 'MedicationDispense',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($data->identifier),
            'partOf' => $this->createReferenceArray($data->part_of),
            'status' => $data->status,
            'category' => [
                'coding' => [
                    [
                        'system' => $data->category ? MedicationDispense::CATEGORY['binding']['valueset']['system'] : null,
                        'code' => $data->category,
                        'display' => $data->category ? MedicationDispense::CATEGORY['binding']['valueset']['display'][$data->category] ?? null : null
                    ]
                ]
            ],
            'medicationReference' => [
                'reference' => $data->medication
            ],
            'subject' => [
                'reference' => $data->subject
            ],
            'context' => [
                'reference' => $data->context
            ],
            'performer' => $this->createPerformerArray($data->performer),
            'location' => [
                'reference' => $data->location
            ],
            'authorizingPrescription' => $this->createReferenceArray($data->authorizing_prescription),
            'quantity' => [
                'value' => $data->quantity_value,
                'unit' => $data->quantity_unit,
                'system' => $data->quantity_system,
                'code' => $data->quantity_code
            ],
            'daysSupply' => [
                'value' => $data->days_supply_value,
                'unit' => $data->days_supply_unit,
                'system' => $data->days_supply_system,
                'code' => $data->days_supply_code
            ],
            'whenPrepared' => $this->parseDateFhir($data->when_prepared),
            'whenHandedOver' => $this->parseDateFhir($data->when_handed_over),
            'note' => $this->createAnnotationArray($data->note),
            'dosageInstruction' => $this->createDosageArray($data->dosageInstruction),
            'substitution' => [
                'wasSubstituted' => $data->substitution_was_substituted,
                'type' => [
                    'coding' => [
                        [
                            'system' => $data->substitution_type ? MedicationDispense::SUBSTITUTION_TYPE['binding']['valueset']['system'] : null,
                            'code' => $data->substitution_type,
                            'display' => $data->substitution_type ? MedicationDispense::SUBSTITUTION_TYPE['binding']['valueset']['display'][$data->substitution_type] ?? null : null
                        ]
                    ]
                ],
                'reason' => $this->createReasonArray($data->substitution->reason ?? null),
                'responsibleParty' => $this->createReferenceArray($data->substitution_responsible_party ?? null)
            ]
        ];
    }


    private function createReasonArray($reasons): array
    {
        $reason = [];

        if (is_array($reasons) || is_object($reasons)) {
            foreach ($reasons as $r) {
                $reason[] = [
                    'coding' => [
                        [
                            'system' => $r ? MedicationDispense::SUBSTITUTION_REASON['binding']['valueset']['system'] : null,
                            'code' => $r,
                            'display' => $r ? MedicationDispense::SUBSTITUTION_REASON['binding']['valueset']['display'][$r] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $reason;
    }


    private function createPerformerArray($performerAttribute): array
    {
        $performer = [];

        if (is_array($performerAttribute) || is_object($performerAttribute)) {
            foreach ($performerAttribute as $p) {
                $performer[] = [
                    'function' => [
                        'coding' => [
                            [
                                'system' => $p->function ? MedicationDispensePerformer::FUNCTION['binding']['valueset']['system'] : null,
                                'code' => $p->function,
                                'display' => $p->function ? MedicationDispensePerformer::FUNCTION['binding']['valueset']['display'][$p->function] ?? null : null
                            ]
                        ]
                    ],
                    'actor' => [
                        'reference' => $p->actor
                    ]
                ];
            }
        }

        return $performer;
    }
}
