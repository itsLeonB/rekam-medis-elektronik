<?php

namespace App\Http\Resources;

use App\Models\CodeSystemLoinc;
use App\Models\Observation;
use App\Models\ObservationReferenceRange;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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
                'category' => $this->createCodeableConceptArray($observation->category),
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
                'issued' => $this->parseDateFhir($observation->issued),
                'performer' => $this->createReferenceArray($observation->performer),
                'dataAbsentReason' => [
                    'coding' => [
                        [
                            'system' => $observation->data_absent_reason ? Observation::DATA_ABSENT_REASON_SYSTEM : null,
                            'code' => $observation->data_absent_reason,
                            'display' => $observation->data_absent_reason ? Observation::DATA_ABSENT_REASON_DISPLAY[$observation->data_absent_reason] : null,
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
                'referenceRange' => $this->createReferenceRangeArray($observation->referenceRange),
                'hasMember' => $this->createReferenceArray($observation->member),
                'derivedFrom' => $this->createReferenceArray($observation->derivedFrom),
                'component' => $this->createComponentArray($observation->component)
            ],
            $observation->effective,
            $observation->value
        );
    }

    private function createReferenceRangeArray(Collection $referenceRangeAttribute)
    {
        $referenceRange = [];

        foreach ($referenceRangeAttribute as $r) {
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
                            'system' => $r->type ? ObservationReferenceRange::TYPE_SYSTEM : null,
                            'code' => $r->type,
                            'display' => $r->type ? ObservationReferenceRange::TYPE_DISPLAY[$r->type] : null,
                        ]
                    ]
                ],
                'appliesTo' => $r->applies_to,
                'age' => [
                    'low' => [
                        'value' => $r->age_low,
                        'unit' => $r->age_low ? 'years' : null,
                        'system' => $r->age_low ? 'http://unitsofmeasure.org' : null,
                        'code' => $r->age_low ? 'a' : null
                    ],
                    'high' => [
                        'value' => $r->age_high,
                        'unit' => $r->age_high ? 'years' : null,
                        'system' => $r->age_high ? 'http://unitsofmeasure.org' : null,
                        'code' => $r->age_high ? 'a' : null
                    ],
                ],
                'text' => $r->text
            ];
        }

        return $referenceRange;
    }

    private function createComponentArray(Collection $componentAttribute)
    {
        $component = [];

        foreach ($componentAttribute as $c) {
            $component[] = merge_array(
                [
                    'code' => [
                        'coding' => [
                            [
                                'system' => $c->code ? CodeSystemLoinc::SYSTEM : null,
                                'code' => $c->code,
                                'display' => $c->code ? CodeSystemLoinc::where('code', $c->code)->first()->display : null
                            ]
                        ],
                    ],
                    'dataAbsentReason' => [
                        'coding' => [
                            [
                                'system' => $c->data_absent_reason ? Observation::DATA_ABSENT_REASON_SYSTEM : null,
                                'code' => $c->data_absent_reason,
                                'display' => $c->data_absent_reason ? Observation::DATA_ABSENT_REASON_DISPLAY[$c->data_absent_reason] : null,
                            ]
                        ]
                    ],
                    'interpretation' => $this->createCodeableConceptArray($c->interpretation),
                    'referenceRange' => $this->createReferenceRangeArray($c->referenceRange)
                ],
                $c->value,
            );
        }

        return $component;
    }
}
