<?php

namespace App\Http\Resources;

use App\Models\Condition;
use App\Models\ConditionEvidence;
use App\Models\ConditionStage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($condition): array
    {
        return $this->mergeArray(
            [
                'resourceType' => 'Condition',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($condition->identifier),
                'clinicalStatus' => [
                    'coding' => [
                        [
                            'system' => $condition->clinical_status ? Condition::CLINICAL_STATUS['binding']['valueset']['system'] : null,
                            'code' => $condition->clinical_status,
                            'display' => $condition->clinical_status ? Condition::CLINICAL_STATUS['binding']['valueset']['display'][$condition->clinical_status] ?? null : null
                        ],
                    ],
                ],
                'verificationStatus' => [
                    'coding' => [
                        [
                            'system' => $condition->verification_status ? Condition::VERIFICATION_STATUS['binding']['valueset']['system'] : null,
                            'code' => $condition->verification_status,
                            'display' => $condition->verification_status ? Condition::VERIFICATION_STATUS['binding']['valueset']['display'][$condition->verification_status] ?? null : null
                        ],
                    ],
                ],
                'category' => $this->createCategoryArray($condition->category),
                'severity' => [
                    'coding' => [
                        [
                            'system' => $condition->severity ? Condition::SEVERITY['binding']['valueset']['system'] : null,
                            'code' => $condition->severity,
                            'display' => $condition->severity ? Condition::SEVERITY['binding']['valueset']['display'][$condition->severity] ?? null : null
                        ]
                    ]
                ],
                'code' => [
                    'coding' => [
                        [
                            'system' => $condition->code_system,
                            'code' => $condition->code_code,
                            'display' => $condition->code_display
                        ]
                    ]
                ],
                'bodySite' => $this->createBodySiteArray($condition->body_site),
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


    private function createBodySiteArray($bodySites): array
    {
        $bodySite = [];

        if (!empty($bodySites)) {
            foreach ($bodySites as $bs) {
                $bodySite[] = [
                    'coding' => [
                        [
                            'system' => $bs ? Condition::BODY_SITE['binding']['valueset']['system'] : null,
                            'code' => $bs,
                            'display' => $bs ? DB::table(Condition::BODY_SITE['binding']['valueset']['table'])
                                ->select('display')
                                ->where('code', '=', $bs)
                                ->first() ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $bodySite;
    }


    private function createCategoryArray($categories): array
    {
        $category = [];

        if (!empty($categories)) {
            foreach ($categories as $c) {
                $category[] = [
                    'coding' => [
                        [
                            'system' => $c ? Condition::CATEGORY['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? Condition::CATEGORY['binding']['valueset']['display'][$c] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $category;
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
                                'system' => $s->summary ? ConditionStage::SUMMARY['binding']['valueset']['system'] : null,
                                'code' => $s->summary,
                                'display' => $s->summary ? ConditionStage::SUMMARY['binding']['valueset']['display'][$s->summary] ?? null : null
                            ]
                        ]
                    ],
                    'assessment' => $this->createReferenceArray($s->assessment),
                    'type' => [
                        'coding' => [
                            [
                                'system' => $s->type ? ConditionStage::TYPE['binding']['valueset']['system'] : null,
                                'code' => $s->type,
                                'display' => $s->type ? DB::table(ConditionStage::TYPE['binding']['valueset']['table'])
                                    ->select('display')
                                    ->where('code', '=', $s->type)
                                    ->first() ?? null : null
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
                    'code' => $this->createCodeArray($e->code),
                    'detail' => $this->createReferenceArray($e->detail)
                ];
            }
        }

        return $evidenceArray;
    }


    private function createCodeArray($codes): array
    {
        $code = [];

        if (!empty($codes)) {
            foreach ($codes as $c) {
                $code[] = [
                    'coding' => [
                        [
                            'system' => $c ? ConditionEvidence::CODE['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? $this->querySnomedCode($c) ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $code;
    }
}
