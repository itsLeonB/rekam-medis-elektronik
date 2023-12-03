<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $patient = $this->getData('patient');

        $data = $this->resourceStructure($patient);

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($patient): array
    {
        return merge_array(
            [
                'resourceType' => 'Patient',
                'id' => $this->satusehat_id,
                'extension' => [
                    [
                        'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace',
                        'valueAddress' => [
                            'city' => $patient->birth_city,
                            'country' => $patient->birth_country,
                        ]
                    ],
                ],
                'identifier' => $this->createIdentifierArray($patient->identifier),
                'active' => $patient->active,
                'name' => [[
                    'use' => 'official',
                    'text' => $patient->name,
                    'prefix' => $patient->prefix,
                    'suffix' => $patient->suffix,
                ]],
                'telecom' => $this->createTelecomArray($patient->telecom),
                'gender' => $patient->gender,
                'birthDate' => Carbon::parse($patient->birth_date)->format('Y-m-d'),
                'address' => $this->createAddressArray($patient->address),
                'contact' => $this->createContactArray($patient->contact),
                'maritalStatus' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus',
                            'code' => $patient->marital_status,
                            'display' => maritalStatusDisplay($patient->marital_status)
                        ]
                    ],
                ],
                'communication' => $this->createCommunicationArray($patient->communication),
                'generalPractitioner' => $this->referenceArray($patient->general_practitioner),
                'link' => $this->createLinkArray($patient->link)
            ],
            $patient->deceased,
            $patient->multiple_birth
        );
    }


    private function createLinkArray($links): array
    {
        $link = [];

        if (!empty($links)) {
            foreach ($links as $l) {
                $link[] = [
                    'other' => [
                        'reference' => $l['other'],
                    ],
                    'type' => $l['type'],
                ];
            }
        }

        return $link;
    }


    private function createCommunicationArray($comms): array
    {
        $communication = [];

        if (!empty($comms)) {
            foreach ($comms as $c) {
                $communication[] = [
                    'language' => [
                        'coding' => [
                            [
                                'system' => $c ? 'urn:ietf:bcp:47' : null,
                                'code' => $c,
                                'display' => $c ? DB::table('codesystem_bcp47')
                                    ->select('display')
                                    ->where('code', $c)
                                    ->first() ?? null : null
                            ],
                        ],
                    ],
                ];
            }
        }

        return $communication;
    }


    private function referenceArray($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => $r,
                ];
            }
        }

        return $reference;
    }


    private function createRelationshipArray($relations): array
    {
        $relationship = [];

        if (!empty($relations)) {
            foreach ($relations as $r) {
                $relationship[] = [
                    'coding' => [
                        [
                            'system' => $r ? 'http://terminology.hl7.org/CodeSystem/v2-0131' : null,
                            'code' => $r,
                            'display' => $r ? relationshipDisplay($r) : null
                        ],
                    ],
                ];
            }
        }

        return $relationship;
    }


    private function createContactArray(Collection $contactAttribute)
    {
        $contact = [];

        if (is_array($contactAttribute) || is_object($contactAttribute)) {
            foreach ($contactAttribute as $c) {
                $extAddress = [
                    [
                        'url' => $c->province ? 'province' : null,
                        'valueCode' => $c->province == 0 ? null : $c->province,
                    ],
                    [
                        'url' => $c->city ? 'city' : null,
                        'valueCode' => $c->city == 0 ? null : $c->city
                    ],
                    [
                        'url' => $c->district ? 'district' : null,
                        'valueCode' => $c->district == 0 ? null : $c->district
                    ],
                    [
                        'url' => $c->village ? 'village' : null,
                        'valueCode' => $c->village == 0 ? null : $c->village
                    ],
                    [
                        'url' => $c->rt ? 'rt' : null,
                        'valueCode' => $c->rt == 0 ? null : $c->rt
                    ],
                    [
                        'url' => $c->rw ? 'rw' : null,
                        'valueCode' => $c->rw == 0 ? null : $c->rw
                    ],
                ];

                $extAddress = removeEmptyValues($extAddress);

                $contact[] = [
                    'relationship' => $this->createRelationshipArray($c->relationship),
                    'name' => [
                        'use' => 'official',
                        'text' => $c->name,
                        'prefix' => $c->prefix == '' ? null : explode(' ', $c->prefix),
                        'suffix' => $c->suffix == '' ? null : explode(' ', $c->suffix),
                    ],
                    'telecom' => $this->createTelecomArray($c->telecom),
                    'address' => [
                        'use' => $c->address_use,
                        'line' => [$c->address_line],
                        'postalCode' => $c->postal_code,
                        'country' => $c->country,
                        'extension' => [
                            [
                                'extension' => $extAddress,
                                'url' => $extAddress ? 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode' : null
                            ]
                        ]
                    ],
                    'gender' => $c->gender
                ];
            }
        }

        return $contact;
    }
}
