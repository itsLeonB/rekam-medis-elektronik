<?php

namespace App\Http\Resources;

use App\Models\Observation;
use App\Models\ObservationComponent;
use App\Models\ObservationReferenceRange;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ObservationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $observation = $this->getData('observation');

        $data = $this->resourceStructure($observation);

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($observation): array
    {
        return merge_array(
            [
                'resourceType' => 'Observation',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($observation->identifier),
                'basedOn' => $this->createReferenceArray($observation->basedOn),
                'partOf' => $this->createReferenceArray($observation->partOf),
                'status' => $observation->status,
                'category' => $this->createCategoryArray($observation->category),
                'code' => [
                    'coding' => [
                        [
                            'system' => $observation->code ? Observation::CODE['binding']['valueset']['system'] : null,
                            'code' => $observation->code,
                            'display' => $observation->code ? DB::table(Observation::CODE['binding']['valueset']['table'])
                                ->select('display')
                                ->where('code', $observation->code)
                                ->first()->display ?? null : null
                        ]
                    ]
                ],
                'subject' => [
                    'reference' => $observation->subject
                ],
                'focus' => $this->createReferenceArray($observation->focus),
                'encounter' => [
                    'reference' => $observation->encounter
                ],
                'issued' => $this->parseDateFhir($observation->issued),
                'performer' => $this->createReferenceArray($observation->performer),
                'dataAbsentReason' => [
                    'coding' => [
                        [
                            'system' => $observation->data_absent_reason ? Observation::DATA_ABSENT_REASON['binding']['valueset']['system'] : null,
                            'code' => $observation->data_absent_reason,
                            'display' => $observation->data_absent_reason ? Observation::DATA_ABSENT_REASON['binding']['valueset']['display'][$observation->data_absent_reason] ?? null : null,
                        ]
                    ]
                ],
                'interpretation' => $this->createInterpretation($observation->interpretation),
                'note' => $this->createAnnotationArray($observation->note),
                'bodySite' => [
                    'coding' => [
                        [
                            'system' => $observation->body_site ? Observation::BODY_SITE['binding']['valueset']['system'] : null,
                            'code' => $observation->body_site,
                            'display' => $observation->body_site ? DB::table(Observation::BODY_SITE['binding']['valueset']['table'])
                                ->select('display')
                                ->where('code', $observation->body_site)
                                ->first()->display ?? null : null,
                        ]
                    ]
                ],
                'method' => [
                    'coding' => [
                        [
                            'system' => $observation->method ? Observation::METHOD['binding']['valueset']['system'] : null,
                            'code' => $observation->method,
                            'display' => $observation->method ? $this->querySnomedCode($observation->method) : null,
                        ]
                    ]
                ],
                'specimen' => [
                    'reference' => $observation->specimen
                ],
                'device' => [
                    'reference' => $observation->device
                ],
                'referenceRange' => $this->createReferenceRangeArray($observation->referenceRange),
                'hasMember' => $this->createReferenceArray($observation->member),
                'derivedFrom' => $this->createReferenceArray($observation->derivedFrom),
                'component' => $this->createComponentArray($observation->component)
            ],
            $observation->effective,
            $observation->value
        );
    }


    private function createInterpretation($interpretations): array
    {
        $interpretation = [];

        if (!empty($interpretations)) {
            foreach ($interpretations as $i) {
                $interpretation[] = [
                    'coding' => [
                        [
                            'system' => $i ? Observation::INTERPRETATION['binding']['valueset']['system'][$i] ?? null : null,
                            'code' => $i,
                            'display' => $i ? Observation::INTERPRETATION['binding']['valueset']['display'][$i] ?? null : null,
                        ]
                    ]
                ];
            }
        }

        return $interpretation;
    }


    private function createCategoryArray($categories): array
    {
        $category = [];

        if (!empty($categories)) {
            foreach ($categories as $c) {
                $category[] = [
                    'coding' => [
                        [
                            'system' => $c ? Observation::CATEGORY['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? Observation::CATEGORY['binding']['valueset']['display'][$c] ?? null : null,
                        ]
                    ]
                ];
            }
        }

        return $category;
    }


    private function createReferenceRangeArray(Collection $referenceRangeAttribute)
    {
        $referenceRange = [];

        foreach ($referenceRangeAttribute as $r) {
            $referenceRange[] = [
                'low' => [
                    'value' => $r->low_value,
                    'unit' => $r->low_unit,
                    'system' => $r->low_system,
                    'code' => $r->low_code,
                ],
                'high' => [
                    'value' => $r->high_value,
                    'unit' => $r->high_unit,
                    'system' => $r->high_system,
                    'code' => $r->high_code,
                ],
                'type' => [
                    'coding' => [
                        [
                            'system' => $r->type ? ObservationReferenceRange::TYPE['binding']['valueset']['system'] : null,
                            'code' => $r->type,
                            'display' => $r->type ? ObservationReferenceRange::TYPE['binding']['valueset']['display'][$r->type] ?? null : null,
                        ]
                    ]
                ],
                'appliesTo' => $this->createAppliesToArray($r->applies_to),
                'age' => [
                    'low' => [
                        'value' => $r->age_low,
                        'unit' => $r->age_low ? 'year' : null,
                        'system' => $r->age_low ? 'http://unitsofmeasure.org' : null,
                        'code' => $r->age_low ? 'a' : null
                    ],
                    'high' => [
                        'value' => $r->age_high,
                        'unit' => $r->age_high ? 'year' : null,
                        'system' => $r->age_high ? 'http://unitsofmeasure.org' : null,
                        'code' => $r->age_high ? 'a' : null
                    ],
                ],
                'text' => $r->text
            ];
        }

        return $referenceRange;
    }


    private function createAppliesToArray($applies): array
    {
        $appliesTo = [];

        if (!empty($applies)) {
            foreach ($applies as $a) {
                $data = DB::table(ObservationReferenceRange::APPLIES_TO['binding']['valueset']['table'])
                    ->select('system', 'display')
                    ->where('code', $a)
                    ->first();
                $appliesTo[] = [
                    'coding' => [
                        [
                            'system' => $a ? $data->system : null,
                            'code' => $a,
                            'display' => $a ? $data->display : null,
                        ]
                    ]
                ];
            }
        }

        return $appliesTo;
    }


    private function createComponentArray($componentAttribute)
    {
        $component = [];

        foreach ($componentAttribute as $c) {
            $component[] = merge_array(
                [
                    'code' => [
                        'coding' => [
                            [
                                'system' => $c->code ? ObservationComponent::CODE['binding']['valueset']['system'] : null,
                                'code' => $c->code,
                                'display' => $c->code ? DB::table(ObservationComponent::CODE['binding']['valueset']['table'])
                                    ->select('display')
                                    ->where('code', $c->code)
                                    ->first()->display ?? null : null
                            ]
                        ],
                    ],
                    'dataAbsentReason' => [
                        'coding' => [
                            [
                                'system' => $c->data_absent_reason ? Observation::DATA_ABSENT_REASON['binding']['valueset']['system'] : null,
                                'code' => $c->data_absent_reason,
                                'display' => $c->data_absent_reason ? Observation::DATA_ABSENT_REASON['binding']['valueset']['display'][$c->data_absent_reason] ?? null : null,
                            ]
                        ]
                    ],
                    'interpretation' => $this->createInterpretation($c->interpretation),
                    'referenceRange' => $this->createReferenceArray($c->referenceRange)
                ],
                $c->value,
            );
        }

        return $component;
    }
}
