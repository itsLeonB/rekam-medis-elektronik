<?php

namespace App\Http\Resources;

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
        $medicationRequest = $this->getData('medicationrequest');

        $data = $this->resourceStructure($medicationRequest);

        $data = removeEmptyValues($data);

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
                        'system' => $data->category_system,
                        'code' => $data->category_code,
                        'display' => $data->category_display
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
            'authorizingPrescription' => $this->createReferenceArray($data->authorizingPrescription),
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
                'wasSubstituted' => $data->substitution->was_substituted,
                'type' => [
                    'coding' => [
                        [
                            'system' => $data->substitution->type_system,
                            'code' => $data->substitution->type_code,
                            'display' => $data->substitution->type_display
                        ]
                    ]
                ],
                'reason' => $this->createCodeableConceptArray($data->substitution->reason),
                'responsibleParty' => $this->createReferenceArray($data->substitution->responsibleParty)
            ]
        ];
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
                                'system' => $p->function_system,
                                'code' => $p->function_code,
                                'display' => $p->function_display
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
