<?php

namespace App\Http\Resources;

use App\Models\CodeSystemClinicalStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConditionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $condition = $this->resource->condition->first();

        $data = merge_array(
            [
                'resourceType' => 'Condition',
                'id' => $this->satusehat_id,
                'identifier' => createIdentifierArray($condition),
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
                'category' => createCodeableConceptArray($condition->category),
                'severity' => [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => $condition->severity,
                            'display' => $this->severityDisplay($condition->severity)
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
                'bodySite' => createCodeableConceptArray($condition->body_site),
                'subject' => [
                    'reference' => $condition->subject
                ],
                'encounter' => [
                    'reference' => $condition->encounter
                ],
                'recordedDate' => $condition->recorded_date,
                'recorder' => [
                    'reference' => $condition->recorder
                ],
                'asserter' => [
                    'reference' => $condition->asserter
                ],
                'stage' => $this->createStageArray($condition),
                'evidence' => $this->createEvidenceArray($condition),
                'note' => createAnnotationArray($condition->note)
            ],
            $condition->onset,
            $condition->abatement,
        );

        $data = removeEmptyValues($data);

        return $data;
    }

    private function severityDisplay($severityCode)
    {
        switch ($severityCode) {
            case '24484000':
                return 'Severe';
                break;
            case '6736007':
                return 'Moderate';
                break;
            case '255604002':
                return 'Mild';
                break;
            default:
                return null;
                break;
        }
    }

    private function createStageArray($condition): array
    {
        $stageData = $condition->stage;

        $stageArray = [];

        foreach ($stageData as $s) {
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
                'assessment' => createReferenceArray($s->assessment),
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

        return $stageArray;
    }

    private function createEvidenceArray($condition): array
    {
        $evidenceData = $condition->evidence;

        $evidenceArray = [];

        foreach ($evidenceData as $e) {
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

        return $evidenceArray;
    }
}
