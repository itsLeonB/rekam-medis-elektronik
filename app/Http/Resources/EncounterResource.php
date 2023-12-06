<?php

namespace App\Http\Resources;

use App\Fhir\Codesystems;
use App\Fhir\Valuesets;
use App\Models\CodeSystemEncounterReason;
use App\Models\Encounter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncounterResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $encounter = $this->getData('encounter');

        $data = $this->resourceStructure($encounter);

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($encounter): array
    {
        return [
            'resourceType' => 'Encounter',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($encounter->identifier),
            'status' => $encounter->status,
            'statusHistory' => $this->createStatusHistoryArray($encounter->statusHistory),
            'class' => [
                'system' => $encounter->class ? Valuesets::EncounterClass['system'] : null,
                'code' => $encounter->class,
                'display' => $encounter->class ? Valuesets::EncounterClass['display'][$encounter->class] ?? null : null
            ],
            'classHistory' => $this->createClassHistoryArray($encounter->classHistory),
            'type' => $this->createTypeArray($encounter->type),
            'serviceType' => [
                'coding' => [
                    [
                        'system' => $encounter->service_type ? Codesystems::ServiceType['system'] : null,
                        'code' => $encounter->service_type,
                        'display' => $encounter->service_type ? DB::table(Codesystems::ServiceType['table'])
                            ->select('display')
                            ->where('code', '=', $encounter->service_type)
                            ->first() ?? null : null
                    ],
                ],
            ],
            'priority' => [
                'coding' => [
                    [
                        'system' => $encounter->priority ? Valuesets::EncounterPriority['system'] : null,
                        'code' => $encounter->priority,
                        'display' => $encounter->priority ? Valuesets::EncounterPriority['display'][$encounter->priority] ?? null : null
                    ],
                ],
            ],
            'subject' => [
                'reference' => $encounter->subject
            ],
            'episodeOfCare' => $this->referenceArray($encounter->episode_of_care),
            'basedOn' => $this->referenceArray($encounter->based_on),
            'participant' => $this->createParticipantArray($encounter->participant),
            'period' => [
                'start' => $this->parseDateFhir($encounter->period_start),
                'end' => $this->parseDateFhir($encounter->period_end),
            ],
            'length' => [
                'value' => $encounter->length_value,
                'comparator' => $encounter->length_comparator,
                'unit' => $encounter->length_unit,
                'system' => $encounter->length_system,
                'code' => $encounter->length_code,
            ],
            'reasonCode' => $this->createReasonCodeArray($encounter->reason_code),
            'reasonReference' => $this->referenceArray($encounter->reason_reference),
            'diagnosis' => $this->createDiagnosisArray($encounter->diagnosis),
            'account' => $this->referenceArray($encounter->account),
            'hospitalization' => [
                'preAdmissionIdentifier' => [
                    'system' => $encounter->hospitalization_preadmission_identifier_system,
                    'use' => $encounter->hospitalization_preadmission_identifier_use,
                    'value' => $encounter->hospitalization_preadmission_identifier_value
                ],
                'origin' => [
                    'reference' => $encounter->hospitalization_origin
                ],
                'admitSource' => [
                    'coding' => [
                        [
                            'system' => $encounter->hospitalization_admit_source ? Codesystems::AdmitSource['system'] : null,
                            'code' => $encounter->hospitalization_admit_source,
                            'display' => $encounter->hospitalization_admit_source ? Codesystems::AdmitSource['display'][$encounter->hospitalization_admit_source] ?? null : null
                        ],
                    ],
                ],
                'reAdmission' => [
                    'coding' => [
                        [
                            'system' => $encounter->hospitalization_re_admission ? Codesystems::v20092['system'] : null,
                            'code' => $encounter->hospitalization_re_admission,
                            'display' => $encounter->hospitalization_re_admission ? Codesystems::v20092['display'] : null
                        ],
                    ],
                ],
                'dietPreference' => $this->createDietArray($encounter->hospitalization_diet_preference),
                'specialArrangement' => $this->createSpecialArrangementArray($encounter->hospitalization_special_arrangement),
                'destination' => [
                    'reference' => $encounter->hospitalization_destination
                ],
                'dischargeDisposition' => [
                    'coding' => [
                        [
                            'system' => $encounter->hospitalization_discharge_disposition ? Codesystems::DischargeDisposition['system'] : null,
                            'code' => $encounter->hospitalization_discharge_disposition,
                            'display' => $encounter->hospitalization_discharge_disposition ? Codesystems::DischargeDisposition['display'][$encounter->hospitalization_discharge_disposition] ?? null : null
                        ],
                    ],
                ],
            ],
            'location' => $this->createLocationArray($encounter->location),
            'serviceProvider' => [
                'reference' => $encounter->service_provider
            ],
            'partOf' => [
                'reference' => $encounter->part_of
            ]
        ];
    }


    private function createLocationArray($locations): array
    {
        $location = [];

        if (!empty($locations)) {
            foreach ($locations as $l) {
                $location[] = [
                    'location' => [
                        'reference' => $l->location,
                    ],
                    'extension' => [
                        [
                            'url' => $l->service_class ? "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass" : null,
                            'valueCodeableConcept' => [
                                'coding' => [
                                    [
                                        'system' => $l->service_class ? Valuesets::LocationServiceClass['system'][$l->service_class] ?? null : null,
                                        'code' => $l->service_class,
                                        'display' => $l->service_class ? Valuesets::LocationServiceClass['display'][$l->service_class] ?? null : null
                                    ]
                                ]
                            ],
                            'upgradeClassIndicator' => [
                                'coding' => [
                                    [
                                        'system' => $l->upgrade_class ? Codesystems::LocationUpgradeClass['system'] : null,
                                        'code' => $l->upgrade_class,
                                        'display' => $l->upgrade_class ? Codesystems::LocationUpgradeClass['display'][$l->upgrade_class] ?? null : null
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        return $location;
    }


    private function createSpecialArrangementArray($specialArrangements): array
    {
        $specialArrangement = [];

        if (!empty($specialArrangements)) {
            foreach ($specialArrangements as $sa) {
                $specialArrangement[] = [
                    'coding' => [
                        [
                            'system' => $sa ? Codesystems::SpecialArrangements['system'] : null,
                            'code' => $sa,
                            'display' => $sa ? Codesystems::SpecialArrangements['display'][$sa] ?? null : null
                        ],
                    ],
                ];
            }
        }

        return $specialArrangement;
    }


    private function createDietArray($diets): array
    {
        $diet = [];

        if (!empty($diets)) {
            foreach ($diets as $d) {
                $diet[] = [
                    'coding' => [
                        [
                            'system' => $d ? Codesystems::Diet['system'] : null,
                            'code' => $d,
                            'display' => $d ? Codesystems::Diet['display'][$d] ?? null : null
                        ],
                    ],
                ];
            }
        }

        return $diet;
    }


    private function referenceArray($refs): array
    {
        $reference = [];

        if (!empty($refs)) {
            foreach ($refs as $r) {
                $reference[] = [
                    'reference' => $r,
                ];
            }
        }

        return $reference;
    }


    private function createTypeArray($types): array
    {
        $type = [];

        if (!empty($types)) {
            foreach ($types as $t) {
                $type[] = [
                    'coding' => [
                        [
                            'system' => $t ? Codesystems::EncounterType['system'] : null,
                            'code' => $t,
                            'display' => $t ? Codesystems::EncounterType['display'][$t] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $type;
    }


    private function createStatusHistoryArray($statusHistoryAttribute): array
    {
        $statusHistory = [];

        if (is_array($statusHistoryAttribute) || is_object($statusHistoryAttribute)) {
            foreach ($statusHistoryAttribute as $sh) {
                $statusHistory[] = [
                    'status' => $sh->status,
                    'period' => [
                        'start' => $this->parseDateFhir($sh->period_start),
                        'end' => $this->parseDateFhir($sh->period_end),
                    ],
                ];
            }
        }

        return $statusHistory;
    }

    private function createClassHistoryArray($classHistoryAttribute): array
    {
        $classHistory = [];

        if (is_array($classHistoryAttribute) || is_object($classHistoryAttribute)) {
            foreach ($classHistoryAttribute as $ch) {
                $classHistory[] = [
                    'class' => [
                        'system' => $ch->class ? Valuesets::EncounterClass['system'] : null,
                        'code' => $ch->class,
                        'display' => $ch->class ? Valuesets::EncounterClass['display'][$ch->class] ?? null : null
                    ],
                    'period' => [
                        'start' => $this->parseDateFhir($ch->period_start),
                        'end' => $this->parseDateFhir($ch->period_end),
                    ],
                ];
            }
        }

        return $classHistory;
    }

    private function createParticipantArray($participantAttribute): array
    {
        $participant = [];

        if (is_array($participantAttribute) || is_object($participantAttribute)) {
            foreach ($participantAttribute as $p) {
                $participant[] = [
                    'type' => $this->createParticipantTypeArray($p->type),
                    'individual' => [
                        'reference' => $p->individual
                    ]
                ];
            }
        }

        return $participant;
    }


    private function createParticipantTypeArray($types): array
    {
        $type = [];

        if (!empty($types)) {
            foreach ($types as $t) {
                $type[] = [
                    'coding' => [
                        [
                            'system' => $t ? Valuesets::EncounterParticipantType['system'][$t] ?? null : null,
                            'code' => $t,
                            'display' => $t ? Valuesets::EncounterParticipantType['display'][$t] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $type;
    }


    private function createReasonCodeArray($reasonCodeAttribute): array
    {
        $reasonCode = [];

        if (is_array($reasonCodeAttribute) || is_object($reasonCodeAttribute)) {
            foreach ($reasonCodeAttribute as $r) {
                $reasonCode[] = [
                    'system' => $r ? Encounter::REASON_CODE['binding']['valueset']['system'] : null,
                    'code' => $r,
                    'display' => $r ? DB::table(Encounter::REASON_CODE['binding']['valueset']['table'])
                        ->select('display')
                        ->where('code', '=', $r)
                        ->first() ?? null : null
                ];
            }
        }

        return $reasonCode;
    }

    private function createDiagnosisArray($diagnosisAttribute): array
    {
        $diagnosis = [];

        if (!empty($diagnosisAttribute)) {
            foreach ($diagnosisAttribute as $d) {
                $diagnosis[] = [
                    'condition' => [
                        'reference' => $d->condition,
                    ],
                    'use' => [
                        'coding' => [
                            [
                                'system' => $d->use ? Codesystems::DiagnosisRole['system'] : null,
                                'code' => $d->use,
                                'display' => $d->use ? Codesystems::DiagnosisRole['display'][$d->use] ?? null : null
                            ],
                        ],
                    ],
                    'rank' => $d->rank
                ];
            }
        }

        return $diagnosis;
    }
}
