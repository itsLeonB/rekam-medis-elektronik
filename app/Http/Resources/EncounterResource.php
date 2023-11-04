<?php

namespace App\Http\Resources;

use App\Models\Encounter;
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
        $encounter = $this->resource->encounter ? $this->resource->encounter->first() : null;

        $data = [
            'resourceType' => 'Encounter',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($encounter->identifier),
            'status' => $encounter->status,
            'statusHistory' => $this->createStatusHistoryArray($encounter->statusHistory),
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => $this->class,
                'display' => encounterClassDisplay($this->class),
            ],
            'classHistory' => $this->createClassHistoryArray($encounter->classHistory),
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

        $data = removeEmptyValues($data);

        return $data;
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
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                        'code' => $ch->class,
                        'display' => encounterClassDisplay($ch->class),
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

    private function createReasonCodeArray(Collection $reasonCodeAttribute): array
    {
        $reasonCode = [];

        if (is_array($reasonCodeAttribute) || is_object($reasonCodeAttribute)) {
            foreach ($reasonCodeAttribute as $r) {
                $reasonCode[] = [
                    'system' => 'http://snomed.info/sct',
                    'code' => $r->code,
                    'display' => encounterReasonDisplay($r->code)
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
                    'dietPreference' => $this->createCodeableConceptArray($h->diet),
                    'specialArrangement' => $this->createCodeableConceptArray($h->specialArrangement),
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
