<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $patient = $this->resource->patient->first();

        $data = merge_array(
            [
                'resourceType' => 'Patient',
                'id' => $this->satusehat_id,
                'extension' => [
                    [
                        'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/birthPlace',
                        'valueAddress' => [
                            'city' => $patient->birth_place,
                        ]
                    ],
                ],
                'identifier' => $this->createIdentifierArray($patient),
                'active' => returnAttribute($patient, ['active']),
                'name' => [[
                    'use' => 'official',
                    'text' => returnAttribute($patient, ['name']),
                    'prefix' => returnAttribute($patient, ['prefix']) == '' ? null : explode(' ', returnAttribute($patient, ['prefix'])),
                    'suffix' => returnAttribute($patient, ['suffix']) == '' ? null : explode(' ', returnAttribute($patient, ['suffix'])),
                ]],
                'telecom' => $this->createTelecomArray($patient),
                'gender' => returnAttribute($patient, ['gender']),
                'birthDate' => Carbon::parse(returnAttribute($patient, ['birth_date']))->format('Y-m-d'),
                'address' => $this->createAddressArray($patient),
                'contact' => $this->createContactArray($patient),
                'maritalStatus' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus',
                            'code' => $patient->marital_status,
                            'display' => $this->displayMaritalStatus($patient->marital_status)
                        ]
                    ],
                ],
                'communication' => [
                    [
                        'language' => [
                            'coding' => [
                                [
                                    'system' => 'urn:ietf:bcp:47',
                                    'code' => $patient->language
                                ],
                            ],
                        ],
                    ],
                ],
                'generalPractitioner' => $this->createGeneralPractitionerArray($patient),
            ],
            $patient->deceased,
            $patient->multiple_birth,
        );

        $data = removeEmptyValues($data);

        return $data;
    }

    private function createIdentifierArray($patient)
    {
        $identifier = [];
        foreach ($patient->identifier as $i) {
            $identifier[] = [
                'system' => $i->system,
                'use' => $i->use,
                'value' => $i->value,
            ];
        }
        return $identifier;
    }

    private function createTelecomArray($patient)
    {
        $telecom = [];

        $telecomData = $patient->telecom;

        if (is_array($telecomData) || is_object($telecomData)) {
            foreach ($telecomData as $t) {
                $telecom[] = [
                    'system' => $t->system,
                    'use' => $t->use,
                    'value' => $t->value,
                ];
            }
        }

        return $telecom;
    }

    private function createAddressArray($patient)
    {
        $addressData = [];

        $address = $patient->address;

        if (is_array($address) || is_object($address)) {
            foreach ($address as $a) {
                $addressData[] = [
                    'use' => $a->use,
                    'line' => [$a->line],
                    'country' => $a->country,
                    'postalCode' => $a->postal_code,
                    'extension' => [
                        [
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode',
                            'extension' => [
                                [
                                    'url' => 'province',
                                    'valueCode' => $a->province == 0 ? null : $a->province,
                                ],
                                [
                                    'url' => 'city',
                                    'valueCode' => $a->city == 0 ? null : $a->city,
                                ],
                                [
                                    'url' => 'district',
                                    'valueCode' => $a->district == 0 ? null : $a->district,
                                ],
                                [
                                    'url' => 'village',
                                    'valueCode' => $a->village == 0 ? null : $a->village,
                                ],
                                [
                                    'url' => 'rt',
                                    'valueCode' => $a->rt == 0 ? null : $a->rt,
                                ],
                                [
                                    'url' => 'rw',
                                    'valueCode' => $a->rw == 0 ? null : $a->rw,
                                ],
                            ]
                        ]
                    ]
                ];
            }
        }

        return $addressData;
    }

    private function createContactArray($patient)
    {
        $contact = [];

        $contactAttribute = $patient->contact;

        if (is_array($contactAttribute) || is_object($contactAttribute)) {
            foreach ($contactAttribute as $c) {
                $contact[] = [
                    'relationship' => [
                        [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                                    'code' => $c->relationship,
                                    'display' => $this->displayRelationship($c->relationship)
                                ],
                            ],
                        ],
                    ],
                    'name' => [
                        'use' => 'official',
                        'text' => $c->name,
                        'prefix' => $c->prefix == '' ? null : explode(' ', $c->prefix),
                        'suffix' => $c->suffix == '' ? null : explode(' ', $c->suffix),
                    ],
                    'telecom' => $this->createTelecomArray($c),
                    'address' => [
                        'use' => $c->address_use,
                        'line' => [$c->address_line],
                        'postalCode' => $c->postal_code,
                        'country' => $c->country,
                        'extension' => [
                            [
                                'extension' => [
                                    [
                                        'url' => 'province',
                                        'valueCode' => $c->province == 0 ? null : $c->province,
                                    ],
                                    [
                                        'url' => 'city',
                                        'valueCode' => $c->city == 0 ? null : $c->city
                                    ],
                                    [
                                        'url' => 'district',
                                        'valueCode' => $c->district == 0 ? null : $c->district
                                    ],
                                    [
                                        'url' => 'village',
                                        'valueCode' => $c->village == 0 ? null : $c->village
                                    ],
                                    [
                                        'url' => 'rt',
                                        'valueCode' => $c->rt == 0 ? null : $c->rt
                                    ],
                                    [
                                        'url' => 'rw',
                                        'valueCode' => $c->rw == 0 ? null : $c->rw
                                    ],
                                ],
                                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode'
                            ]
                        ]
                    ],
                    'gender' => $c->gender
                ];
            }
        }

        return $contact;
    }

    private function createGeneralPractitionerArray($patient)
    {
        $generalPractitioner = [];

        $generalPractitionerAttribute = $patient->generalPractitioner;

        if (is_array($generalPractitionerAttribute) || is_object($generalPractitionerAttribute)) {
            foreach ($generalPractitionerAttribute as $g) {
                $generalPractitioner[] = [
                    'reference' => $g->reference,
                ];
            }
        }

        return $generalPractitioner;
    }

    private function displayMaritalStatus($maritalCode)
    {
        switch ($maritalCode) {
            case 'A':
                $maritalDisplay = 'Annulled';
                break;
            case 'D':
                $maritalDisplay = 'Divorced';
                break;
            case 'I':
                $maritalDisplay = 'Interloctury';
                break;
            case 'L':
                $maritalDisplay = 'Legally Separated';
                break;
            case 'M':
                $maritalDisplay = 'Married';
                break;
            case 'C':
                $maritalDisplay = 'Common Law';
                break;
            case 'P':
                $maritalDisplay = 'Polygamous';
                break;
            case 'T':
                $maritalDisplay = 'Domestic partner';
                break;
            case 'U':
                $maritalDisplay = 'Unmarried';
                break;
            case 'S':
                $maritalDisplay = 'Never Married';
                break;
            case 'W':
                $maritalDisplay = 'Widowed';
                break;
            default:
                $maritalDisplay = 'Annulled';
                break;
        }
        return $maritalDisplay;
    }

    private function displayRelationship($relationshipCode)
    {
        switch ($relationshipCode) {
            case 'BP':
                return 'Billing contact person';
                break;
            case 'CP':
                return 'Contact person';
                break;
            case 'EP':
                return 'Emergency contact person';
                break;
            case 'PR':
                return 'Person preparing referral';
                break;
            case 'E':
                return 'Employer';
                break;
            case 'C':
                return 'Emergency Contact';
                break;
            case 'F':
                return 'Federal Agency';
                break;
            case 'I':
                return 'Insurance Company';
                break;
            case 'N':
                return 'Next-of-Kin';
                break;
            case 'S':
                return 'State Agency';
                break;
            case 'O':
                return 'Other';
                break;
            case 'U':
                return 'Unknown';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
}
