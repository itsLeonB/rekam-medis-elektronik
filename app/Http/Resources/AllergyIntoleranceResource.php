<?php

namespace App\Http\Resources;

use App\Models\AllergyIntolerance;
use App\Models\AllergyIntoleranceReaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AllergyIntoleranceResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $allergy = $this->getData('allergyIntolerance');

        $data = $this->resourceStructure($allergy);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($allergy): array
    {
        return $this->mergeArray(
            [
                'resourceType' => 'AllergyIntolerance',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($allergy->identifier),
                'clinicalStatus' => [
                    'coding' => [
                        [
                            'system' => $allergy->clinical_status ? AllergyIntolerance::CLINICAL_STATUS['binding']['valueset']['system'] : null,
                            'code' => $allergy->clinical_status,
                            'display' => $allergy->clinical_status ? AllergyIntolerance::CLINICAL_STATUS['binding']['valueset']['display'][$allergy->clinical_status] ?? null : null
                        ]
                    ],
                ],
                'verificationStatus' => [
                    'coding' => [
                        [
                            'system' => $allergy->verification_status ? AllergyIntolerance::VERIFICATION_STATUS['binding']['valueset']['system'] : null,
                            'code' => $allergy->verification_status,
                            'display' => $allergy->verification_status ? AllergyIntolerance::VERIFICATION_STATUS['binding']['valueset']['display'][$allergy->verification_status] ?? null : null
                        ]
                    ],
                ],
                'type' => $allergy->type,
                'category' => $allergy->category,
                'criticality' => $allergy->criticality,
                'code' => [
                    'coding' => [
                        [
                            'system' => $allergy->code_system,
                            'code' => $allergy->code_code,
                            'display' => $allergy->code_display
                        ]
                    ]
                ],
                'patient' => [
                    'reference' => $allergy->patient
                ],
                'encounter' => [
                    'reference' => $allergy->encounter
                ],
                'recordedDate' => $this->parseDateFhir($allergy->recorded_date),
                'recorder' => [
                    'reference' => $allergy->recorder
                ],
                'asserter' => [
                    'reference' => $allergy->asserter
                ],
                'lastOcurrence' => $this->parseDateFhir($allergy->last_occurence),
                'note' => $this->createAnnotationArray($allergy->note),
                'reaction' => $this->createReactionArray($allergy->reaction)
            ],
            $allergy->onset,
        );
    }

    private function createReactionArray(Collection $reactionAttribute)
    {
        $reaction = [];

        if (is_array($reactionAttribute) || is_object($reactionAttribute)) {
            foreach ($reactionAttribute as $r) {
                $reaction[] = [
                    'substance' => [
                        'coding' => [
                            [
                                'system' => $r->substance_system,
                                'code' => $r->substance_code,
                                'display' => $r->substance_display
                            ]
                        ]
                    ],
                    'manifestation' => $this->createManifestationArray($r->manifestation),
                    'description' => $r->description,
                    'onset' => $this->parseDateFhir($r->onset),
                    'severity' => $r->severity,
                    'exposureRoute' => [
                        'coding' => [
                            [
                                'system' => $r->exposure_route ? AllergyIntoleranceReaction::EXPOSURE_ROUTE['binding']['valueset']['system'] : null,
                                'code' => $r->exposure_route,
                                'display' => $r->exposure_route ? AllergyIntoleranceReaction::EXPOSURE_ROUTE['binding']['valueset']['display'][$r->exposure_route] ?? null : null
                            ]
                        ]
                    ],
                    'note' => $this->createAnnotationArray($r->note)
                ];
            }
        }

        return $reaction;
    }


    private function createManifestationArray($manifestations): array
    {
        $manifestation = [];

        if (is_array($manifestations) || is_object($manifestations)) {
            foreach ($manifestations as $m) {
                $manifestation[] = [
                    'coding' => [
                        [
                            'system' => $m ? AllergyIntoleranceReaction::MANIFESTATION['binding']['valueset']['system'] : null,
                            'code' => $m,
                            'display' => $m ? AllergyIntoleranceReaction::MANIFESTATION['binding']['valueset']['display'][$m] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $manifestation;
    }
}
