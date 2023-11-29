<?php

namespace App\Http\Resources;

use App\Models\CodeSystemLoinc;
use App\Models\ImagingStudy;
use App\Models\ImagingStudySeries;
use App\Models\ImagingStudySeriesInstance;
use Illuminate\Http\Request;

class ImagingStudyResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imagingStudy = $this->getData('imagingstudy');

        $data = $this->resourceStructure($imagingStudy);

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure($imagingStudy): array
    {
        return [
            'resourceType' => 'ImagingStudy',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($imagingStudy->identifier),
            'status' => $imagingStudy->status,
            'modality' => $this->createModalityArray($imagingStudy->modality),
            'subject' => $imagingStudy->subject,
            'encounter' => $imagingStudy->encounter,
            'started' => $this->parseDateFhir($imagingStudy->started),
            'basedOn' => $this->referenceArray($imagingStudy->based_on),
            'referrer' => $imagingStudy->referrer,
            'interpreter' => $this->referenceArray($imagingStudy->interpreter),
            'endpoint' => $this->referenceArray($imagingStudy->endpoint),
            'numberOfSeries' => $imagingStudy->series_num,
            'numberOfInstances' => $imagingStudy->instances_num,
            'procedureReference' => [
                'reference' => $imagingStudy->procedure_reference
            ],
            'procedureCode' => $this->createProcedureCodeArray($imagingStudy->procedure_code),
            'location' => [
                'reference' => $imagingStudy->location
            ],
            'reasonCode' => $this->createCodeableConceptArray($imagingStudy->reasonCode),
            'reasonReference' => $this->referenceArray($imagingStudy->reason_reference),
            'note' => $this->createAnnotationArray($imagingStudy->note),
            'description' => $imagingStudy->description,
            'series' => $this->createSeriesArray($imagingStudy->series)
        ];
    }


    private function createSeriesArray($seriesAttribute): array
    {
        $series = [];

        if (!empty($seriesAttribute)) {
            foreach ($seriesAttribute as $s) {
                $series[] = [
                    'uid' => $s->uid,
                    'number' => $s->number,
                    'modality' => [
                        'system' => $s->modality ? ImagingStudySeries::MODALITY_SYSTEM : null,
                        'code' => $s->modality,
                        'display' => $s->modality ? ImagingStudySeries::MODALITY_DISPLAY[$s->modality] : null
                    ],
                    'description' => $s->description,
                    'numberOfInstances' => $s->instances_num,
                    'endpoint' => $this->referenceArray($s->endpoint),
                    'bodySite' => [
                        'system' => $s->body_site_system,
                        'code' => $s->body_site_code,
                        'display' => $s->body_site_display
                    ],
                    'laterality' => [
                        'system' => $s->laterality ? ImagingStudySeries::LATERALITY_SYSTEM : null,
                        'code' => $s->laterality,
                        'display' => $s->laterality ? ImagingStudySeries::LATERALITY_DISPLAY[$s->laterality] : null
                    ],
                    'started' => $this->parseDateFhir($s->started),
                    'performer' => $this->createPerformerArray($s->performer),
                    'instance' => $this->createInstanceArray($s->instance)
                ];
            }
        }

        return $series;
    }


    private function createInstanceArray($instances): array
    {
        $instance = [];

        if (!empty($instances)) {
            foreach ($instances as $i) {
                $instance[] = [
                    'uid' => $i->uid,
                    'sopClass' => [
                        'system' => $i->sop_class ? ImagingStudySeriesInstance::SOPCLASS_SYSTEM : null,
                        'code' => $i->sop_class,
                        'display' => $i->sop_class ? ImagingStudySeriesInstance::SOPCLASS_DISPLAY[$i->sop_class] : null
                    ],
                    'number' => $i->number,
                    'title' => $i->title,
                ];
            }
        }

        return $instance;
    }


    private function createPerformerArray($performers): array
    {
        $performer = [];

        if (!empty($performers)) {
            foreach ($performers as $p) {
                $performer[] = [
                    'function' => [
                        'coding' => [
                            [
                                'system' => $p ? ImagingStudySeries::PERFORMER_FUNCTION_SYSTEM : null,
                                'code' => $p['function'],
                                'display' => $p ? ImagingStudySeries::PERFORMER_FUNCTION_DISPLAY[$p['function']] : null
                            ]
                        ]
                    ],
                    'actor' => [
                        'reference' => $p['actor']
                    ]
                ];
            }
        }

        return $performer;
    }


    private function createProcedureCodeArray($codeableConcepts): array
    {
        $codeableConcept = [];

        if (!empty($codeableConcepts)) {
            foreach ($codeableConcepts as $cc) {
                $codeableConcept[] = [
                    'coding' => [
                        [
                            'system' => $cc ? CodeSystemLoinc::SYSTEM : null,
                            'code' => $cc,
                            'display' => $cc ? CodeSystemLoinc::where('code', $cc)->first()->display ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $codeableConcept;
    }


    private function referenceArray($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => $r
                ];
            }
        }

        return $reference;
    }


    private function createModalityArray($modalities): array
    {
        $modality = [];

        if (!empty($modalities)) {
            foreach ($modalities as $m) {
                $modality[] = [
                    'system' => $m ? ImagingStudy::MODALITY_SYSTEM : null,
                    'code' => $m,
                    'display' => $m ? ImagingStudy::MODALITY_DISPLAY[$m] : null
                ];
            }
        }

        return $modality;
    }
}
