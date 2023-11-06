<?php

namespace App\Http\Resources;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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

        if ($patient == null) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

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
                'identifier' => $this->createIdentifierArray($patient->identifier),
                'active' => $patient->active,
                'name' => [[
                    'use' => 'official',
                    'text' => $patient->name,
                    'prefix' => $patient->prefix == '' ? null : explode(' ', $patient->prefix),
                    'suffix' => $patient->suffix == '' ? null : explode(' ', $patient->suffix),
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
                'generalPractitioner' => $this->createGeneralPractitionerArray($patient->generalPractitioner),
            ],
            $patient->deceased,
            $patient->multiple_birth
        );

        $data = removeEmptyValues($data);

        return $data;
    }

    private function createContactArray(Collection $contactAttribute)
    {
        $contact = [];

        if (is_array($contactAttribute) || is_object($contactAttribute)) {
            foreach ($contactAttribute as $c) {
                $contact[] = [
                    'relationship' => [
                        [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0131',
                                    'code' => $c->relationship,
                                    'display' => relationshipDisplay($c->relationship)
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
                    'telecom' => $this->createTelecomArray($c->telecom),
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

    private function createGeneralPractitionerArray(Collection $generalPractitionerAttribute)
    {
        $generalPractitioner = [];

        if (is_array($generalPractitionerAttribute) || is_object($generalPractitionerAttribute)) {
            foreach ($generalPractitionerAttribute as $g) {
                $generalPractitioner[] = [
                    'reference' => $g->reference,
                ];
            }
        }

        return $generalPractitioner;
    }
}
