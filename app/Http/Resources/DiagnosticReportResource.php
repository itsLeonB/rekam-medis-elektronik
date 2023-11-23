<?php

namespace App\Http\Resources;

use App\Models\CodeSystemLoinc;
use App\Models\DiagnosticReport;
use Illuminate\Http\Request;

class DiagnosticReportResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $diagnostic = $this->getData('diagnostic');

        $data = $this->resourceStructure($diagnostic);

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure($diagnostic): array
    {
        return merge_array(
            [
                'resourceType' => 'DiagnosticReport',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($diagnostic->identifier),
                'basedOn' => $this->referenceArray($diagnostic->based_on),
                'status' => $diagnostic->status,
                'category' => $this->categoryArray($diagnostic->category),
                'code' => [
                    'coding' => [
                        [
                            'system' => $diagnostic->code ? CodeSystemLoinc::SYSTEM : null,
                            'code' => $diagnostic->code,
                            'display' => $diagnostic->code ? CodeSystemLoinc::where('code', $diagnostic->code)->first()->display ?? null : null
                        ]
                    ]
                ],
                'subject' => [
                    'reference' => $diagnostic->subject
                ],
                'encounter' => [
                    'reference' => $diagnostic->encounter
                ],
                'issued' => $diagnostic->issued,
                'performer' => $this->referenceArray($diagnostic->performer),
                'resultsInterpreter' => $this->referenceArray($diagnostic->results_interpreter),
                'specimen' => $this->referenceArray($diagnostic->specimen),
                'result' => $this->referenceArray($diagnostic->result),
                'imagingStudy' => $this->referenceArray($diagnostic->imaging_study),
                'media' => $this->mediaArray($diagnostic->media),
                'conclusion' => $diagnostic->conclusion,
                'conclusionCode' => $this->createCodeableConceptArray($diagnostic->conclusionCode)
            ],
            $diagnostic->effective
        );
    }


    private function mediaArray($mediaAttribute): array
    {
        $media = [];

        if (!empty($mediaAttribute)) {
            foreach ($mediaAttribute as $m) {
                $media[] = [
                    'comment' => $m->comment,
                    'link' => [
                        'reference' => $m->link
                    ]
                ];
            }
        }

        return $media;
    }


    private function categoryArray($categoryAttribute): array
    {
        $category = [];

        if (!empty($categoryAttribute)) {
            foreach ($categoryAttribute as $c) {
                $category[] = [
                    'coding' => [
                        [
                            'system' => $c ? DiagnosticReport::CATEGORY_SYSTEM : null,
                            'code' => $c,
                            'display' => $c ? DiagnosticReport::CATEGORY_DISPLAY[$c] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $category;
    }


    private function referenceArray($referenceAttribute): array
    {
        $reference = [];

        if (!empty($referenceAttribute)) {
            foreach ($referenceAttribute as $r) {
                $reference[] = [
                    'reference' => $r
                ];
            }
        }

        return $reference;
    }
}
