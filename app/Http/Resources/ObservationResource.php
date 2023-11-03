<?php

namespace App\Http\Resources;

use App\Models\CodeSystemLoinc;
use App\Models\Observation;
use App\Models\ObservationReferenceRange;
use Illuminate\Http\Request;

class ObservationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $observation = $this->resource->observation ? $this->resource->observation->first() : null;

        $data = merge_array(
            [
                'resourceType' => 'Observation',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($observation),
                'basedOn' => $this->createReferenceArray($observation->basedOn),
                'partOf' => $this->createReferenceArray($observation->partOf),
                'status' => $observation->status,
                'category' => $this->createCategoryArray($observation),
                'code' => [
                    'coding' => [
                        [
                            'system' => $observation->code_system,
                            'code' => $observation->code_code,
                            'display' => $observation->code_display
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
                'issued' => $observation->issued,
                'performer' => $this->createReferenceArray($observation->performer),
                'dataAbsentReason' => [
                    'coding' => [
                        [
                            'system' => Observation::DATA_ABSENT_REASON_SYSTEM[$observation->data_absent_reason],
                            'code' => $observation->data_absent_reason,
                            'display' => Observation::DATA_ABSENT_REASON_DISPLAY[$observation->data_absent_reason],
                        ]
                    ]
                ],
                'interpretation' => $this->createCodeableConceptArray($observation->interpretation),
                'note' => $this->createAnnotationArray($observation->note),
                'bodySite' => [
                    'coding' => [
                        [
                            'system' => $observation->body_site_system,
                            'code' => $observation->body_site_code,
                            'display' => $observation->body_site_display,
                        ]
                    ]
                ],
                'method' => [
                    'coding' => [
                        [
                            'system' => $observation->method_system,
                            'code' => $observation->method_code,
                            'display' => $observation->method_display,
                        ]
                    ]
                ],
                'specimen' => [
                    'reference' => $observation->specimen
                ],
                'device' => [
                    'reference' => $observation->device
                ],
                'referenceRange' => $this->createReferenceRangeArray($observation),
                'hasMember' => $this->createReferenceArray($observation->member),
                'derivedFrom' => $this->createReferenceArray($observation->derivedFrom),
                'component' => $this->createComponentArray($observation)
            ],
            $observation->effective,
            $observation->value,
        );

        $data = removeEmptyValues($data);
        $data = $this->parseDate($data);

        return $data;
    }

    private function createReferenceRangeArray($observation)
    {
        $referenceRange = [];

        foreach ($observation->referenceRange as $r) {
            $referenceRange[] = [
                'low' => [
                    'value' => $r->value_low,
                    'unit' => $r->unit,
                    'system' => $r->system,
                    'code' => $r->code,
                ],
                'high' => [
                    'value' => $r->value_high,
                    'unit' => $r->unit,
                    'system' => $r->system,
                    'code' => $r->code,
                ],
                'type' => [
                    'coding' => [
                        [
                            'system' => ObservationReferenceRange::SYSTEM,
                            'code' => $r->type,
                            'display' => ObservationReferenceRange::DISPLAY[$r->type],
                        ]
                    ]
                ],
                'appliesTo' => $r->applies_to,
                'age' => [
                    'low' => [
                        'value' => $r->age_low,
                        'unit' => 'years',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'a'
                    ],
                    'high' => [
                        'value' => $r->age_high,
                        'unit' => 'years',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'a'
                    ],
                ],
                'text' => $r->text
            ];
        }

        return $referenceRange;
    }

    private function createComponentArray($observation)
    {
        $component = [];

        foreach ($observation->component as $c) {
            $component[] = merge_array(
                [
                    'code' => [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code' => $c->code,
                                'display' => CodeSystemLoinc::where('code', $c->code)->first()->display ?? null
                            ]
                        ],
                    ],
                    'dataAbsentReason' => [
                        'coding' => [
                            [
                                'system' => Observation::DATA_ABSENT_REASON_SYSTEM[$c->data_absent_reason],
                                'code' => $observation->data_absent_reason,
                                'display' => Observation::DATA_ABSENT_REASON_DISPLAY[$c->data_absent_reason],
                            ]
                        ]
                    ],
                    'interpretation' => $this->createCodeableConceptArray($c->interpretation),
                    'referenceRange' => $this->createReferenceRangeArray($c)
                ],
                $c->value,
            );
        }

        return $component;
    }
}
