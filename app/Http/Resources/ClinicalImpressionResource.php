<?php

namespace App\Http\Resources;

use App\Models\Fhir\{
    ClinicalImpression,
    ClinicalImpressionFinding,
    ClinicalImpressionInvestigation
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClinicalImpressionResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $clinicalImpression = $this->getData('clinicalImpression');

        $data = $this->resourceStructure($clinicalImpression);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($clinicalImpression): array
    {
        return $this->mergeArray(
            [
                'resourceType' => 'ClinicalImpression',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($clinicalImpression->identifier),
                'status' => $clinicalImpression->status,
                'statusReason' => [
                    'coding' => [
                        [
                            'system' => $clinicalImpression->status_reason_code ? ClinicalImpression::STATUS_REASON_CODE['binding']['valueset']['system'] : null,
                            'code' => $clinicalImpression->status_reason_code,
                            'display' => $clinicalImpression->status_reason_code ? DB::table(ClinicalImpression::STATUS_REASON_CODE['binding']['valueset']['table'])
                                ->where('code', $clinicalImpression->status_reason_code)
                                ->value('display_en') ?? null : null
                        ]
                    ],
                    'text' => $clinicalImpression->status_reason_text
                ],
                'code' => [
                    'coding' => [
                        [
                            'system' => $clinicalImpression->code_system,
                            'code' => $clinicalImpression->code_code,
                            'display' => $clinicalImpression->code_display
                        ]
                    ],
                    'text' => $clinicalImpression->code_text
                ],
                'description' => $clinicalImpression->description,
                'subject' => [
                    'reference' => $clinicalImpression->subject
                ],
                'encounter' => [
                    'reference' => $clinicalImpression->encounter
                ],
                'date' => $this->parseDateFhir($clinicalImpression->date),
                'assessor' => [
                    'reference' => $clinicalImpression->assessor
                ],
                'previous' => [
                    'reference' => $clinicalImpression->previous
                ],
                'problem' => $this->createReferenceArray($clinicalImpression->problem),
                'investigation' => $this->createInvestigationArray($clinicalImpression->investigation),
                'protocol' => $clinicalImpression->protocol,
                'summary' => $clinicalImpression->summary,
                'finding' => $this->createFindingArray($clinicalImpression->finding),
                'prognosisCodeableConcept' => $this->createPrognosisArray($clinicalImpression->prognosis_codeable_concept),
                'prognosisReference' => $this->createReferenceArray($clinicalImpression->prognosis_reference),
                'supportingInfo' => $this->createReferenceArray($clinicalImpression->supporting_info),
                'note' => $this->createAnnotationArray($clinicalImpression->note)
            ],
            $clinicalImpression->effective
        );
    }


    private function createPrognosisArray($prognoses): array
    {
        $prognosis = [];

        if (is_array($prognoses) || is_object($prognoses)) {
            foreach ($prognoses as $p) {
                $prognosis[] = [
                    'coding' => [
                        [
                            'system' => $p ? ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['system'] : null,
                            'code' => $p,
                            'display' => $p ? ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['display'][$p] ?? null : null
                        ]
                    ],
                ];
            }
        }

        return $prognosis;
    }


    private function createInvestigationArray($investigationAttribute): array
    {
        $investigation = [];

        if (is_array($investigationAttribute) || is_object($investigationAttribute)) {
            foreach ($investigationAttribute as $i) {
                $investigation[] = [
                    'code' => [
                        'coding' => [
                            [
                                'system' => $i->code ? ClinicalImpressionInvestigation::CODE['binding']['valueset']['system'] : null,
                                'code' => $i->code,
                                'display' => $i->code ? ClinicalImpressionInvestigation::CODE['binding']['valueset']['display'][$i->code] ?? null : null
                            ]
                        ],
                        'text' => $i->code_text
                    ],
                    'item' => $this->createReferenceArray($i->item)
                ];
            }
        }

        return $investigation;
    }


    private function createFindingArray($findingAttribute): array
    {
        $finding = [];

        if (is_array($findingAttribute) || is_object($findingAttribute)) {
            foreach ($findingAttribute as $f) {
                $finding[] = [
                    'itemCodeableConcept' => [
                        'coding' => [
                            [
                                'system' => $f->item_codeable_concept ? ClinicalImpressionFinding::ITEM_CODEABLE_CONCEPT['binding']['valueset']['system'] : null,
                                'code' => $f->item_codeable_concept,
                                'display' => $f->item_codeable_concept ? DB::table(ClinicalImpressionFinding::ITEM_CODEABLE_CONCEPT['binding']['valueset']['table'])
                                    ->where('code', $f->item_codeable_concept)
                                    ->value('display_en') ?? null : null
                            ]
                        ]
                    ],
                    'itemReference' => [
                        'reference' => $f->item_reference
                    ],
                    'basis' => $f->basis
                ];
            }
        }

        return $finding;
    }
}
