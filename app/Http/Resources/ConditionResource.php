<?php

namespace App\Http\Resources;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ConditionResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $condition = $this->getData('condition');

        if ($condition == null) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data = merge_array(
            [
                'resourceType' => 'Condition',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($condition->identifier),
                'clinicalStatus' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                            'code' => $condition->clinical_status,
                            'display' => clinicalStatus($condition->clinical_status)->display
                        ],
                    ],
                ],
                'verificationStatus' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/condition-ver-status',
                            'code' => $condition->verification_status,
                            'display' => verificationStatus($condition->verification_status)->display
                        ],
                    ],
                ],
                'category' => $this->createCodeableConceptArray($condition->category),
                'severity' => [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => $condition->severity,
                            'display' => Condition::SEVERITY_DISPLAY[$condition->severity] ?? null
                        ]
                    ]
                ],
                'code' => [
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => $condition->code,
                            'display' => icd10Display($condition->code)
                        ]
                    ]
                ],
                'bodySite' => $this->createCodeableConceptArray($condition->body_site),
                'subject' => [
                    'reference' => $condition->subject
                ],
                'encounter' => [
                    'reference' => $condition->encounter
                ],
                'recordedDate' => $this->parseDateFhir($condition->recorded_date),
                'recorder' => [
                    'reference' => $condition->recorder
                ],
                'asserter' => [
                    'reference' => $condition->asserter
                ],
                'stage' => $this->createStageArray($condition->stage),
                'evidence' => $this->createEvidenceArray($condition->evidence),
                'note' => $this->createAnnotationArray($condition->note)
            ],
            $condition->onset,
            $condition->abatement,
        );

        $data = removeEmptyValues($data);

        return $data;
    }

    private function createStageArray(Collection $stageAttribute): array
    {
        $stageArray = [];

        if (is_array($stageAttribute) || is_object($stageAttribute)) {
            foreach ($stageAttribute as $s) {
                $stageArray[] = [
                    'summary' => [
                        'coding' => [
                            [
                                'system' => $s->summary_system,
                                'code' => $s->summary_code,
                                'display' => $s->summary_display
                            ]
                        ]
                    ],
                    'assessment' => $this->createReferenceArray($s->assessment),
                    'type' => [
                        'coding' => [
                            [
                                'system' => $s->type_system,
                                'code' => $s->type_code,
                                'display' => $s->type_display
                            ]
                        ]
                    ]
                ];
            }
        }

        return $stageArray;
    }

    private function createEvidenceArray(Collection $evidenceAttribute): array
    {
        $evidenceArray = [];

        if (is_array($evidenceAttribute) || is_object($evidenceAttribute)) {
            foreach ($evidenceAttribute as $e) {
                $evidenceArray[] = [
                    'code' => [
                        [
                            'coding' => [
                                [
                                    'system' => $e->system,
                                    'code' => $e->code,
                                    'display' => $e->display,
                                ]
                            ]
                        ]
                    ],
                    'detail' => [
                        [
                            'reference' => $e->detail_reference
                        ]
                    ]
                ];
            }
        }

        return $evidenceArray;
    }
}
