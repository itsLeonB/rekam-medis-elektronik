<?php

namespace App\Http\Resources;

use App\Models\Fhir\Medication;
use Illuminate\Http\Request;

class MedicationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medication = $this->getData('medication');

        $data = $this->resourceStructure($medication);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($medication): array
    {
        return [
            'resourceType' => 'Medication',
            'id' => $this->satusehat_id,
            'extension' => [
                [
                    'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
                    'valueCodeableConcept' => [
                        'coding' => [
                            [
                                'system' => $medication->medication_type ? Medication::MEDICATION_TYPE['binding']['valueset']['system'] : null,
                                'code' => $medication->medication_type,
                                'display' => $medication->medication_type ? Medication::MEDICATION_TYPE['binding']['valueset']['display'][$medication->medication_type] ?? null : null
                            ]
                        ]
                    ]
                ]
            ],
            'identifier' => $this->createIdentifierArray($medication->identifier),
            'code' => [
                'coding' => [
                    [
                        'system' => $medication->system,
                        'code' => $medication->code,
                        'display' => $medication->display
                    ]
                ]
            ],
            'status' => $medication->status,
            'manufacturer' => [
                'reference' => $medication->manufacturer
            ],
            'form' => [
                'coding' => [
                    [
                        'system' => $medication->form ? Medication::FORM['binding']['valueset']['system'] : null,
                        'code' => $medication->form,
                        'display' => $medication->form ? Medication::FORM['binding']['valueset']['display'][$medication->form] ?? null : null
                    ]
                ]
            ],
            'amount' => [
                'numerator' => [
                    'value' => $medication->amount_numerator_value,
                    'comparator' => $medication->amount_numerator_comparator,
                    'unit' => $medication->amount_numerator_unit,
                    'system' => $medication->amount_numerator_system,
                    'code' => $medication->amount_numerator_code
                ],
                'denominator' => [
                    'value' => $medication->amount_denominator_value,
                    'comparator' => $medication->amount_denominator_comparator,
                    'unit' => $medication->amount_denominator_unit,
                    'system' => $medication->amount_denominator_system,
                    'code' => $medication->amount_denominator_code
                ]
            ],
            'ingredient' => $this->createIngredientArray($medication->ingredient),
            'batch' => [
                'lotNumber' => $medication->batch_lot_number,
                'expirationDate' => $this->parseDateFhir($medication->batch_expiration_date)
            ]
        ];
    }

    private function createIngredientArray($ingredientAttribute): array
    {
        $ingredient = [];

        if (is_array($ingredientAttribute) || is_object($ingredientAttribute)) {
            foreach ($ingredientAttribute as $i) {
                $ingredient[] = [
                    'itemCodeableConcept' => [
                        'coding' => [
                            [
                                'system' => $i->system,
                                'code' => $i->code,
                                'display' => $i->display
                            ]
                        ]
                    ],
                    'isActive' => $i->is_active,
                    'strength' => [
                        'numerator' => [
                            'value' => $i->strength_numerator_value,
                            'comparator' => $i->strength_numerator_comparator,
                            'unit' => $i->strength_numerator_unit,
                            'system' => $i->strength_numerator_system,
                            'code' => $i->strength_numerator_code
                        ],
                        'denominator' => [
                            'value' => $i->strength_denominator_value,
                            'comparator' => $i->strength_denominator_comparator,
                            'unit' => $i->strength_denominator_unit,
                            'system' => $i->strength_denominator_system,
                            'code' => $i->strength_denominator_code
                        ]
                    ]
                ];
            }
        }

        return $ingredient;
    }
}
