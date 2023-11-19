<?php

namespace App\Http\Resources;

use App\Models\CodeSystemEncounterReason;
use App\Models\CodeSystemServiceType;
use App\Models\Encounter;
use App\Models\EncounterDiagnosis;
use App\Models\EncounterHospitalization;
use App\Models\EncounterParticipant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
                'system' => $encounter->class ? Encounter::CLASS_SYSTEM : null,
                'code' => $encounter->class,
                'display' => $encounter->class ? Encounter::CLASS_DISPLAY[$encounter->class] : null
            ],
            'classHistory' => $this->createClassHistoryArray($encounter->classHistory),
            'serviceType' => [
                'coding' => [
                    [
                        'system' => $encounter->service_type ? CodeSystemServiceType::SYSTEM : null,
                        'code' => $encounter->service_type,
                        'display' => $encounter->service_type ? CodeSystemServiceType::where('code', $encounter->service_type)->first()->display : null
                    ],
                ],
            ],
            'priority' => [
                'coding' => [
                    [
                        'system' => $encounter->priority ? Encounter::PRIORITY_SYSTEM : null,
                        'code' => $encounter->priority,
                        'display' => $encounter->priority ? Encounter::PRIORITY_DISPLAY[$encounter->priority] : null
                    ],
                ],
            ],
            'subject' => [
                'reference' => $encounter->subject
            ],
            'episodeOfCare' => [
                [
                    'reference' => $encounter->episode_of_care
                ],
            ],
            'basedOn' => [
                [
                    'reference' => $encounter->based_on
                ]
            ],
            'participant' => $this->createParticipantArray($encounter->participant),
            'period' => [
                'start' => $this->parseDateFhir($encounter->period_start),
                'end' => $this->parseDateFhir($encounter->period_end),
            ],
            'reasonCode' => $this->createReasonCodeArray($encounter->reason),
            'reasonReference' => $this->createReferenceArray($encounter->reason),
            'diagnosis' => $this->createDiagnosisArray($encounter->diagnosis),
            'account' => [
                [
                    'reference' => $encounter->account
                ]
            ],
            'hospitalization' => $this->createHospitalizationArray($encounter->hospitalization),
            'location' => [
                [
                    'location' => [
                        'reference' => $encounter->location
                    ]
                ]
            ],
            'serviceProvider' => [
                'reference' => $encounter->service_provider
            ],
            'partOf' => [
                'reference' => $encounter->part_of
            ]
        ];
    }

    private function createStatusHistoryArray(Collection $statusHistoryAttribute): array
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

    private function createClassHistoryArray(Collection $classHistoryAttribute): array
    {
        $classHistory = [];

        if (is_array($classHistoryAttribute) || is_object($classHistoryAttribute)) {
            foreach ($classHistoryAttribute as $ch) {
                $classHistory[] = [
                    'class' => [
                        'system' => $ch->class ? Encounter::CLASS_SYSTEM : null,
                        'code' => $ch->class,
                        'display' => $ch->class ? Encounter::CLASS_DISPLAY[$ch->class] : null
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

    private function createParticipantArray(Collection $participantAttribute): array
    {
        $participant = [];

        if (is_array($participantAttribute) || is_object($participantAttribute)) {
            foreach ($participantAttribute as $p) {
                $participant[] = [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system' => $p->type ? EncounterParticipant::TYPE_SYSTEM[$p->type] : null,
                                    'code' => $p->type,
                                    'display' => $p->type ? EncounterParticipant::TYPE_DISPLAY[$p->type] : null
                                ]
                            ]
                        ]
                    ],
                    'individual' => [
                        'reference' => $p->individual
                    ]
                ];
            }
        }

        return $participant;
    }

    private function createReasonCodeArray(Collection $reasonCodeAttribute): array
    {
        $reasonCode = [];

        if (is_array($reasonCodeAttribute) || is_object($reasonCodeAttribute)) {
            foreach ($reasonCodeAttribute as $r) {
                $reasonCode[] = [
                    'system' => $r->code ? CodeSystemEncounterReason::SYSTEM : null,
                    'code' => $r->code,
                    'display' => $r->code ? CodeSystemEncounterReason::where('code', $r->code)->first()->display : null
                ];
            }
        }

        return $reasonCode;
    }

    private function createDiagnosisArray(Collection $diagnosisAttribute): array
    {
        $diagnosis = [];

        if (isset($diagnosisAttribute)) {
            foreach ($diagnosisAttribute as $d) {
                $diagnosis[] = [
                    'condition' => [
                        'reference' => $d->condition_reference,
                        'display' => $d->condition_reference
                    ],
                    'use' => [
                        'coding' => [
                            [
                                'system' => $d->use ? EncounterDiagnosis::USE_SYSTEM : null,
                                'code' => $d->use,
                                'display' => $d->use ? EncounterDiagnosis::USE_DISPLAY[$d->use] : null
                            ],
                        ],
                    ],
                    'rank' => $d->rank
                ];
            }
        }

        return $diagnosis;
    }

    private function createHospitalizationArray(Collection $hospitalizationAttribute): array
    {
        $hospitalization = [];

        if (is_array($hospitalizationAttribute) || is_object($hospitalizationAttribute)) {
            foreach ($hospitalizationAttribute as $h) {
                $hospitalization[] = [
                    'preAdmissionIdentifier' => [
                        'system' => $h->preadmission_identifier_system,
                        'use' => $h->preadmission_identifier_use,
                        'value' => $h->preadmission_identifier_value
                    ],
                    'origin' => [
                        'reference' => $h->origin
                    ],
                    'admitSource' => [
                        'coding' => [
                            [
                                'system' => $h->admit_source ? EncounterHospitalization::ADMIT_SOURCE_SYSTEM : null,
                                'code' => $h->admit_source,
                                'display' => $h->admit_source ? EncounterHospitalization::ADMIT_SOURCE_DISPLAY[$h->admit_source] : null
                            ],
                        ],
                    ],
                    'reAdmission' => [
                        'coding' => [
                            [
                                'system' => $h->re_admission ? EncounterHospitalization::READMISSION_SYSTEM : null,
                                'code' => $h->re_admission,
                                'display' => $h->re_admission ? EncounterHospitalization::READMISSION_DISPLAY : null
                            ],
                        ],
                    ],
                    'dietPreference' => $this->createCodeableConceptArray($h->diet),
                    'specialArrangement' => $this->createCodeableConceptArray($h->specialArrangement),
                    'destination' => [
                        'reference' => $h->destination
                    ],
                    'dischargeDisposition' => [
                        'coding' => [
                            [
                                'system' => $h->discharge_disposition ? EncounterHospitalization::DISCHARGE_DISPOSITION_SYSTEM : null,
                                'code' => $h->discharge_disposition,
                                'display' => $h->discharge_disposition ? EncounterHospitalization::DISCHARGE_DISPOSITION_DISPLAY[$h->discharge_disposition] : null
                            ],
                        ],
                    ],

                ];
            }
        }

        return $hospitalization;
    }
}
