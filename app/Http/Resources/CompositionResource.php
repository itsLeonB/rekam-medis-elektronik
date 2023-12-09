<?php

namespace App\Http\Resources;

use App\Models\Composition;
use App\Models\CompositionEvent;
use App\Models\CompositionSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompositionResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $composition = $this->getData('composition');

        $data = $this->resourceStructure($composition);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($composition): array
    {
        return [
            'resourceType' => 'Composition',
            'id' => $this->satusehat_id,
            'identifier' => [
                'system' => $composition->identifier_system,
                'use' => $composition->identifier_use,
                'value' => $composition->identifier_value
            ],
            'status' => $composition->status,
            'type' => [
                'coding' => [
                    [
                        'system' => $composition->type_system,
                        'code' => $composition->type_code,
                        'display' => $composition->type_display
                    ]
                ]
            ],
            'category' => $this->createCategoryArray($composition->category),
            'subject' => [
                'reference' => $composition->subject
            ],
            'encounter' => [
                'reference' => $composition->encounter
            ],
            'date' => $this->parseDateFhir($composition->date),
            'author' => $this->createReferenceArray($composition->author),
            'title' => $composition->title,
            'confidentiality' => $composition->confidentiality,
            'attester' => $this->createAttesterArray($composition->attester),
            'custodian' => [
                'reference' => $composition->custodian
            ],
            'relatesTo' => $this->createRelatesToArray($composition->relatesTo),
            'event' => $this->createEventArray($composition->event),
            'section' => $this->createSectionArray($composition->section),
        ];
    }


    private function createCategoryArray($categories): array
    {
        $category = [];

        if (!empty($categories)) {
            foreach ($categories as $c) {
                $category[] = [
                    'coding' => [
                        [
                            'system' => $c ? Composition::CATEGORY['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? Composition::CATEGORY['binding']['valueset']['display'][$c] : null,
                        ]
                    ]
                ];
            }
        }

        return $category;
    }


    private function createAttesterArray($attesterAttribute): array
    {
        $attester = [];

        if (is_array($attesterAttribute) || is_object($attesterAttribute)) {
            foreach ($attesterAttribute as $a) {
                $attester[] = [
                    'mode' => $a->mode,
                    'time' => $this->parseDateFhir($a->time),
                    'party' => [
                        'reference' => $a->party
                    ]
                ];
            }
        }

        return $attester;
    }

    private function createRelatesToArray($relatesToAttribute): array
    {
        $relatesTo = [];

        if (is_array($relatesToAttribute) || is_object($relatesToAttribute)) {
            foreach ($relatesToAttribute as $rt) {
                $relatesTo[] = $this->mergeArray(
                    ['code' => $rt->code],
                    $rt->target
                );
            }
        }

        return $relatesTo;
    }

    private function createEventArray($eventAttribute): array
    {
        $event = [];

        if (is_array($eventAttribute) || is_object($eventAttribute)) {
            foreach ($eventAttribute as $e) {
                $event[] = [
                    'code' => $this->createCodeArray($e->code),
                    'period' => [
                        'start' => $this->parseDateFhir($e->period_start),
                        'end' => $this->parseDateFhir($e->period_end)
                    ],
                    'detail' => $this->createReferenceArray($e->detail)
                ];
            }
        }

        return $event;
    }


    private function createCodeArray($codes): array
    {
        $code = [];

        if (is_array($codes) || is_object($codes)) {
            foreach ($codes as $c) {
                $code[] = [
                    'coding' => [
                        [
                            'system' => $c ? CompositionEvent::CODE['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? DB::table(CompositionEvent::CODE['binding']['valueset']['table'])
                                ->select('display')
                                ->where('code', $c)
                                ->first()
                                ->display ?? null : null,
                        ]
                    ]
                ];
            }
        }

        return $code;
    }


    private function createSectionArray($sectionAttribute): array
    {
        $section = [];

        if (is_array($sectionAttribute) || is_object($sectionAttribute)) {
            foreach ($sectionAttribute as $s) {
                $section[] = $this->mergeArray(
                    [
                        'title' => $s->title,
                        'code' => [
                            'coding' => [
                                [
                                    'system' => $s->code ? CompositionSection::CODE['binding']['valueset']['system'] : null,
                                    'code' => $s->code,
                                    'display' => $s->code ? CompositionSection::CODE['binding']['valueset']['display'][$s->code] ?? null : null,
                                ]
                            ]
                        ],
                        'author' => $this->createReferenceArray($s->author),
                        'focus' => [
                            'reference' => $s->focus
                        ],
                        'text' => [
                            'status' => $s->text_status,
                            'div' => $s->text_div
                        ],
                        'mode' => $s->mode,
                        'orderedBy' => [
                            'coding' => [
                                [
                                    'system' => $s->ordered_by ? CompositionSection::ORDERED_BY['binding']['valueset']['system'] : null,
                                    'code' => $s->ordered_by,
                                    'display' => $s->ordered_by ? CompositionSection::ORDERED_BY['binding']['valueset']['display'][$s->ordered_by] ?? null : null,
                                ]
                            ]
                        ],
                        'entry' => $this->createReferenceArray($s->entry),
                        'emptyReason' => [
                            'coding' => [
                                [
                                    'system' => $s->empty_reason ? CompositionSection::EMPTY_REASON['binding']['valueset']['system'] : null,
                                    'code' => $s->empty_reason,
                                    'display' => $s->empty_reason ? CompositionSection::EMPTY_REASON['binding']['valueset']['display'][$s->empty_reason] ?? null : null,
                                ]
                            ]
                        ],
                    ],
                    $s->section
                );
            }
        }

        return $section;
    }
}
