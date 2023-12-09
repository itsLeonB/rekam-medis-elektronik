<?php

namespace App\Http\Resources;

use App\Models\Fhir\{
    Patient,
    PatientCommunication,
    PatientContact
};
use Carbon\Carbon;
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

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($patient): array
    {
        return $this->mergeArray(
            [
                'resourceType' => 'Patient',
                'id' => $this->satusehat_id,
                'extension' => [
                    [
                        'url' => ($patient->birth_city || $patient->birth_country) ? 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace' : null,
                        'valueAddress' => [
                            'city' => $patient->birth_city,
                            'country' => $patient->birth_country,
                        ]
                    ],
                ],
                'identifier' => $this->createIdentifierArray($patient->identifier),
                'active' => $patient->active,
                'name' => $this->createHumanNameArray($patient->name),
                'telecom' => $this->createTelecomArray($patient->telecom),
                'gender' => $patient->gender,
                'birthDate' => Carbon::parse($patient->birth_date)->format('Y-m-d'),
                'address' => $this->createAddressArray($patient->address),
                'contact' => $this->createContactArray($patient->contact),
                'photo' => $this->createPhotoArray($patient->photo),
                'maritalStatus' => [
                    'coding' => [
                        [
                            'system' => $patient->marital_status ? Patient::MARITAL_STATUS['binding']['valueset']['system'] : null,
                            'code' => $patient->marital_status,
                            'display' => $patient->marital_status ? Patient::MARITAL_STATUS['binding']['valueset']['display'][$patient->marital_status] ?? null : null
                        ]
                    ],
                ],
                'communication' => $this->createCommunicationArray($patient->communication),
                'generalPractitioner' => $this->createReferenceArray($patient->general_practitioner),
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
                        'reference' => $l->other,
                    ],
                    'type' => $l->type,
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
                                'system' => $c->language ? PatientCommunication::LANGUAGE['binding']['valueset']['system'] : null,
                                'code' => $c->language,
                                'display' => $c->language ? DB::table(PatientCommunication::LANGUAGE['binding']['valueset']['table'])
                                    ->where('code', $c->language)
                                    ->value('display') : null,
                            ],
                        ],
                    ],
                    'preferred' => $c->preferred,
                ];
            }
        }

        return $communication;
    }


    private function createRelationshipArray($relations): array
    {
        $relationship = [];

        if (!empty($relations)) {
            foreach ($relations as $r) {
                $relationship[] = [
                    'coding' => [
                        [
                            'system' => $r ? PatientContact::RELATIONSHIP['binding']['valueset']['system'] : null,
                            'code' => $r,
                            'display' => $r ? PatientContact::RELATIONSHIP['binding']['valueset']['display'][$r] ?? null : null,
                        ],
                    ],
                ];
            }
        }

        return $relationship;
    }


    private function createContactArray($contactAttribute)
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

                $extAddress = $this->removeEmptyValues($extAddress);

                $contact[] = [
                    'relationship' => $this->createRelationshipArray($c->relationship),
                    'name' => [
                        'text' => $c->name,
                        'family' => $c->family,
                        'given' => $c->given,
                        'prefix' => $c->prefix,
                        'suffix' => $c->suffix,
                    ],
                    'telecom' => $this->createTelecomArray($c->telecom),
                    'address' => [
                        'use' => $c->address_use,
                        'type' => $c->address_type,
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
                    'gender' => $c->gender,
                    'organization' => [
                        'reference' => $c->organization,
                    ],
                    'period' => [
                        'start' => $this->parseDateFhir($c->period_start),
                        'end' => $this->parseDateFhir($c->period_end),
                    ],
                ];
            }
        }

        return $contact;
    }
}
