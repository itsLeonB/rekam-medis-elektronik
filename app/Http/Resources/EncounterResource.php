<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EncounterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $encounter = $this->resource->encounter->first();

        $data = [
            'resourceType' => 'Encounter',
            'id' => $this->satusehat_id,
            'identifier' => createIdentifierArray($encounter),
            'status' => $encounter->status,
            'statusHistory' => $this->createStatusHistoryArray($encounter),
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => $this->class,
                'display' => encounterClassDisplay($this->class),
            ],
            'classHistory' => $this->createClassHistoryArray($encounter),
            'serviceType' => [
                'coding' => [
                    [
                        'system' => $encounter->service_type == null ? null : 'http://terminology.hl7.org/CodeSystem/service-type',
                        'code' => $encounter->service_type,
                        'display' => serviceTypeDisplay($encounter->service_type)
                    ],
                ],
            ],
            'priority' => [
                'coding' => [
                    [
                        'system' => $encounter->priority == null ? null : 'http://terminology.hl7.org/CodeSystem/v3-ActPriority',
                        'code' => $encounter->priority,
                        'display' => priorityDisplay($encounter->priority)
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
            'participant' => $this->createParticipantArray($encounter),
            'period' => [
                'start' => $encounter->period_start,
                'end' => $encounter->period_end,
            ],
            'reasonCode' => $this->createReasonCodeArray($encounter),
            'reasonReference' => createReferenceArray($encounter->reason),
            'diagnosis' => $this->createDiagnosisArray($encounter->diagnosis),
            'account' => [
                [
                    'reference' => $encounter->account
                ]
            ],
            'hospitalization' => $this->createHospitalizationArray($encounter),
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

        $data = removeEmptyValues($data);

        return $data;
    }

    private function createStatusHistoryArray($encounter): array
    {
        $statusHistory = [];

        if (is_array($encounter->statusHistory) || is_object($encounter->statusHistory)) {
            foreach ($encounter->statusHistory as $sh) {
                $statusHistory[] = [
                    'status' => $sh->status,
                    'period' => [
                        'start' => $sh->period_start,
                        'end' => $sh->period_end,
                    ],
                ];
            }
        }

        return $statusHistory;
    }

    private function createClassHistoryArray($encounter): array
    {
        $classHistory = [];

        if (is_array($encounter->classHistory) || is_object($encounter->classHistory)) {
            foreach ($encounter->classHistory as $ch) {
                $classHistory[] = [
                    'class' => [
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                        'code' => $ch->class,
                        'display' => encounterClassDisplay($ch->class),
                    ],
                    'period' => [
                        'start' => $ch->period_start,
                        'end' => $ch->period_end,
                    ],
                ];
            }
        }

        return $classHistory;
    }

    private function createParticipantArray($encounter): array
    {
        $participant = [];

        if (is_array($encounter->participant) || is_object($encounter->participant)) {
            foreach ($encounter->participant as $p) {
                $participant[] = [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system' => participantType($p->type)->system,
                                    'code' => $p->type,
                                    'display' => participantType($p->type)->display
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

    private function createReasonCodeArray($encounter): array
    {
        $reasonCode = [];

        if (is_array($encounter->reason) || is_object($encounter->reason)) {
            foreach ($encounter->reason as $r) {
                $reasonCode[] = [
                    'system' => 'http://snomed.info/sct',
                    'code' => $r->code,
                    'display' => encounterReasonDisplay($r->code)
                ];
            }
        }

        return $reasonCode;
    }

    private function createDiagnosisArray($encounter): array
    {
        $diagnosis = [];

        if (isset($encounter->diagnosis)) {
            foreach ($encounter->diagnosis as $d) {
                $diagnosis[] = [
                    'condition' => [
                        'reference' => $d->condition_reference,
                        'display' => $d->condition_reference
                    ],
                    'use' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
                                'code' => $d->use,
                                'display' => diagnosisUseDisplay($d->use)
                            ],
                        ],
                    ],
                    'rank' => $d->rank
                ];
            }
        }

        return $diagnosis;
    }

    private function createHospitalizationArray($encounter): array
    {
        $hospitalization = [];

        if (is_array($encounter->hospitalization) || is_object($encounter->hospitalization)) {
            foreach ($encounter->hospitalization as $h) {
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
                                'system' => 'http://terminology.hl7.org/CodeSystem/admit-source',
                                'code' => $h->admit_source,
                                'display' => admitSource($h->admit_source)->display
                            ],
                        ],
                    ],
                    'reAdmission' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0092',
                                'code' => $h->re_admission,
                                'display' => $h->re_admission == 'R' ? 'Re-admssion' : null
                            ],
                        ],
                    ],
                    'dietPreference' => createCodeableConceptArray($h->diet),
                    'specialArrangement' => createCodeableConceptArray($h->specialArrangement),
                    'destination' => [
                        'reference' => $h->destination
                    ],
                    'dischargeDisposition' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0092',
                                'code' => $h->discharge_disposition,
                                'display' => dischargeDisposition($h->discharge_disposition)->display
                            ],
                        ],
                    ],

                ];
            }
        }

        return $hospitalization;
    }
}
