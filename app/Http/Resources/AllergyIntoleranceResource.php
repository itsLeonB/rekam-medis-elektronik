<?php

namespace App\Http\Resources;

use App\Models\AllergyIntolerance;
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
        $allergyIntolerance = $this->resource->allergyIntolerance ? $this->resource->allergyIntolerance->first() : null;

        $data = merge_array(
            [
                'resourceType' => 'AllergyIntolerance',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($allergyIntolerance->identifier),
                'clinicalStatus' => [
                    'coding' => [
                        [
                            'system' => AllergyIntolerance::CLINICAL_STATUS_SYSTEM,
                            'code' => $allergyIntolerance->clinical_status,
                            'display' => AllergyIntolerance::CLINICAL_STATUS_DISPLAY_DEFINITION[$allergyIntolerance->clinical_status]['display'] ?? null
                        ]
                    ],
                ],
                'verificationStatus' => [
                    'coding' => [
                        [
                            'system' => AllergyIntolerance::VERIFICATION_STATUS_SYSTEM,
                            'code' => $allergyIntolerance->verification_status,
                            'display' => AllergyIntolerance::VERIFICATION_STATUS_DISPLAY_DEFINITION[$allergyIntolerance->verification_status]['display'] ?? null
                        ]
                    ],
                ],
                'type' => $allergyIntolerance->type,
                'category' => $this->createCategoryArray($allergyIntolerance),
                'criticality' => $allergyIntolerance->criticality,
                'code' => [
                    'coding' => [
                        [
                            'system' => $allergyIntolerance->code_system,
                            'code' => $allergyIntolerance->code_code,
                            'display' => $allergyIntolerance->code_display
                        ]
                    ]
                ],
                'patient' => [
                    'reference' => $allergyIntolerance->patient
                ],
                'encounter' => [
                    'reference' => $allergyIntolerance->encounter
                ],
                'recordedDate' => $this->parseDateFhir($allergyIntolerance->recorded_date),
                'recorder' => [
                    'reference' => $allergyIntolerance->recorder
                ],
                'asserter' => [
                    'reference' => $allergyIntolerance->asserter
                ],
                'lastOcurrence' => $this->parseDateFhir($allergyIntolerance->last_occurence),
                'note' => $this->createAnnotationArray($allergyIntolerance->note),
                'reaction' => $this->createReactionArray($allergyIntolerance->reaction)
            ],
            $allergyIntolerance->onset
        );

        $data = removeEmptyValues($data);

        return $data;
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
                    'manifestation' => $this->createCodeableConceptArray($r->manifestation),
                    'description' => $r->description,
                    'onset' => $this->parseDateFhir($r->onset),
                    'severity' => $r->severity,
                    'exposureRoute' => [
                        'coding' => [
                            [
                                'system' => $r->exposure_route_system,
                                'code' => $r->exposure_route_code,
                                'display' => $r->exposure_route_display
                            ]
                        ]
                    ],
                    'note' => $this->createAnnotationArray($r->note)
                ];
            }
        }

        return $reaction;
    }

    private function createCategoryArray(AllergyIntolerance $allergyIntolerance)
    {
        $category = [];

        if ($allergyIntolerance->category_food) {
            $category[] = 'food';
        }

        if ($allergyIntolerance->category_medication) {
            $category[] = 'medication';
        }

        if ($allergyIntolerance->category_environment) {
            $category[] = 'environment';
        }

        if ($allergyIntolerance->category_biologic) {
            $category[] = 'biologic';
        }

        return $category;
    }
}
