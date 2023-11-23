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

        $data = $this->resourceStructure($condition);

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($condition): array
    {
        return merge_array(
            [
                'resourceType' => 'Condition',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($condition->identifier),
                'clinicalStatus' => [
                    'coding' => [
                        [
                            'system' => $condition->clinical_status ? Condition::CLINICAL_STATUS_SYSTEM : null,
                            'code' => $condition->clinical_status,
                            'display' => $condition->clinical_status ? Condition::CLINICAL_STATUS_DISPLAY[$condition->clinical_status] : null
                        ],
                    ],
                ],
                'verificationStatus' => [
                    'coding' => [
                        [
                            'system' => $condition->verification_status ? Condition::VERIFICATION_STATUS_SYSTEM : null,
                            'code' => $condition->verification_status,
                            'display' => $condition->verification_status ? Condition::VERIFICATION_STATUS_DISPLAY[$condition->verification_status] : null
                        ],
                    ],
                ],
                'category' => $this->createCodeableConceptArray($condition->category),
                'severity' => [
                    'coding' => [
                        [
                            'system' => $condition->severity ? Condition::SEVERITY_SYSTEM : null,
                            'code' => $condition->severity,
                            'display' => $condition->severity ? Condition::SEVERITY_DISPLAY[$condition->severity] : null
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
            $condition->abatement
        );
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
