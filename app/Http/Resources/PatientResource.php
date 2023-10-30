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
        $patient = $request['patient'];
        $identifier = $request['identifier'];
        $telecom = $request['telecom'];
        $address = $request['address'];
        $contact = $request['contact'];
        $generalPractitioner = $request['general_practitioner'];
        $maritalDisplay = $request['marital_display'];

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
                'identifier' => $identifier,
                'active' => $patient->active,
                'name' => [[
                    'use' => 'official',
                    'text' => $patient->name,
                    'prefix' => $patient->prefix == '' ? null : explode(' ', $patient->prefix),
                    'suffix' => $patient->suffix == '' ? null : explode(' ', $patient->suffix),
                ]],
                'telecom' => $telecom,
                'gender' => $patient->gender,
                'birthDate' => Carbon::parse($patient->birth_date)->format('Y-m-d'),
                'address' => $address,
                'contact' => $contact,
                'maritalStatus' => [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-MaritalStatus',
                            'code' => $patient->marital_status,
                            'display' => $maritalDisplay
                        ]
                    ],
                ],
                'multipleBirthBoolean' => $patient->multiple_birth,
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
                'generalPractitioner' => $generalPractitioner,
            ],
            $patient->deceased,
        );

        $data = removeEmptyValues($data);

        return $data;
    }

    private function createIdentifierArray($patient) {
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

    private function createTelecomArray($patient) {
        $telecom = [];
        foreach ($patient->telecom as $t) {
            $telecom[] = [
                'system' => $t->system,
                'use' => $t->use,
                'value' => $t->value,
            ];
        }
        return $telecom;
    }

    private function createAddressArray($patient) {
        $address = [];
        foreach ($patient->address as $a) {
            $address[] = [
                'use' => $a->use,
                'line' => [$a->line],
                'postalCode' => $a->postal_code,
                'country' => $a->country,
                'extension' => [
                    [
                        'extension' => [
                            [
                                'url' => 'province',
                                'valueCode' => $a->province,
                            ],
                            [
                                'url' => 'city',
                                'valueCode' => $a->city,
                            ],
                            [
                                'url' => 'district',
                                'valueCode' => $a->district,
                            ],
                            [
                                'url' => 'village',
                                'valueCode' => $a->village,
                            ],
                            [
                                'url' => 'rt',
                                'valueCode' => $a->rt,
                            ],
                            [
                                'url' => 'rw',
                                'valueCode' => $a->rw,
                            ],
                        ],
                        'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode'
                    ]
                ],
            ];
        }
        return $address;
    }

    private function createContactArray($patient) {
        $contact = [];
        foreach ($patient->contact as $c) {
            $contactTelecom = $this->createTelecomArray($c);

            switch ($c->relationship) {
                case 'C':
                    $relationDisplay = 'Emergency Contact';
                    break;
                case 'E':
                    $relationDisplay = 'Employer';
                    break;
                case 'F':
                    $relationDisplay = 'Federal Agency';
                    break;
                case 'I':
                    $relationDisplay = 'Insurance Company';
                    break;
                case 'N':
                    $relationDisplay = 'Next-of-Kin';
                    break;
                case 'S':
                    $relationDisplay = 'State Agency';
                    break;
                case 'U':
                    $relationDisplay = 'Unknown';
                    break;
                case 'T':
                default:
                    $relationDisplay = 'Unknown';
                    break;
            }
            $relationshipDisplay = $relationDisplay;
            $contact[] = [
                'relationship' => [
                    [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                                'code' => $c->relationship,
                                'display' => $relationshipDisplay,
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
                'telecom' => $contactTelecom,
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
                                    'valueCode' => $c->district == 0 ? null : $c->district,
                                ],
                                [
                                    'url' => 'village',
                                    'valueCode' => $c->village == 0 ? null : $c->village,
                                ],
                                [
                                    'url' => 'rt',
                                    'valueCode' => $c->rt == 0 ? null : $c->rt,
                                ],
                                [
                                    'url' => 'rw',
                                    'valueCode' => $c->rw == 0 ? null : $c->rw,
                                ],
                            ],
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode'
                        ]
                    ]
                ],
                'gender' => $c->gender,
            ];
        }
        return $contact;
    }

    public function displayMaritalStatus($maritalCode) {
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

    private function createGeneralPractitionerArray($patient) {
        $generalPractitioner = [];
        foreach ($patient->generalPractitioner as $gp) {
            $generalPractitioner[] = [
                'reference' => $gp->reference,
            ];
        }
        return $generalPractitioner;
    }
}
