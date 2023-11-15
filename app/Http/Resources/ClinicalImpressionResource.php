<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

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

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($clinicalImpression): array
    {
        return merge_array(
            [
                'resourceType' => 'ClinicalImpression',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($clinicalImpression->identifier),
                'status' => $clinicalImpression->status,
                'statusReason' => [
                    'coding' => [
                        [
                            'system' => $clinicalImpression->status_reason_system,
                            'code' => $clinicalImpression->status_reason_code,
                            'display' => $clinicalImpression->status_reason_display
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
                    ]
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
                'protocol' => $this->createProtocolArray($clinicalImpression->protocol),
                'summary' => $clinicalImpression->summary,
                'finding' => $this->createFindingArray($clinicalImpression->finding),
                'prognosisCodeableConcept' => $this->createCodeableConceptArray($clinicalImpression->prognosis),
                'prognosisReference' => $this->createReferenceArray($clinicalImpression->prognosis),
                'supportingInfo' => $this->createReferenceArray($clinicalImpression->supportingInfo),
                'note' => $this->createAnnotationArray($clinicalImpression->note)
            ],
            $clinicalImpression->effective
        );
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
                                'system' => $i->system,
                                'code' => $i->code,
                                'display' => $i->display
                            ]
                        ],
                        'text' => $i->text
                    ],
                    'item' => $this->createReferenceArray($i->item)
                ];
            }
        }

        return $investigation;
    }

    private function createProtocolArray($protocolAttribute): array
    {
        $protocol = [];

        if (is_array($protocolAttribute) || is_object($protocolAttribute)) {
            foreach ($protocolAttribute as $p) {
                $protocol[] = $p->uri;
            }
        }

        return $protocol;
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
                                'system' => $f->system,
                                'code' => $f->code,
                                'display' => $f->display
                            ]
                        ]
                    ],
                    'itemReference' => [
                        'reference' => $f->reference
                    ],
                    'basis' => $f->basis
                ];
            }
        }

        return $finding;
    }
}
