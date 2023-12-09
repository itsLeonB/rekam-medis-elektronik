<?php

namespace App\Http\Resources;

use App\Models\Codesystems\AdministrativeCode;
use App\Models\Organization;
use App\Models\OrganizationContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $observation = $this->getData('organization');

        $data = $this->resourceStructure($observation);

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure($organization): array
    {
        return [
            'resourceType' => 'Organization',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($organization->identifier),
            'active' => $organization->active,
            'type' => $this->createTypeArray($organization->type),
            'name' => $organization->name,
            'alias' => $organization->alias,
            'telecom' => $this->createTelecomArray($organization->telecom),
            'address' => $this->createAddressArray($organization->address),
            'partOf' => [
                'reference' => $organization->part_of
            ],
            'contact' => $this->createContactArray($organization->contact),
            'endpoint' => $this->createReferenceArray($organization->endpoint)
        ];
    }


    private function createContactArray($contacts): array
    {
        $contact = [];

        if (!empty($contacts)) {
            foreach ($contacts as $c) {
                $contact[] = [
                    'purpose' => [
                        'coding' => [
                            [
                                'system' => $c->purpose ? OrganizationContact::PURPOSE['binding']['valueset']['system'] : null,
                                'code' => $c->purpose,
                                'display' => $c->purpose ? OrganizationContact::PURPOSE['binding']['valueset']['display'][$c->purpose] ?? null : null
                            ]
                        ]
                    ],
                    'name' => [
                        'text' => $c->name_text,
                        'family' => $c->name_family,
                        'given' => $c->name_given,
                        'prefix' => $c->name_prefix,
                        'suffix' => $c->name_suffix
                    ],
                    'telecom' => $this->createTelecomArray($c->telecom),
                    'address' => [
                        'use' => $c->address_use,
                        'type' => $c->address_type,
                        'line' => $c->address_line,
                        'country' => $c->country,
                        'postalCode' => $c->postal_code,
                        'city' => DB::table(OrganizationContact::ADMINISTRATIVE_CODE['binding']['valueset']['table'])->where('nama_kabko', $c->city)->value('display') ?? null,
                        'extension' => [
                            [
                                'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/AdministrativeCode',
                                'extension' => [
                                    [
                                        'url' => $c->province ? 'province' : null,
                                        'valueCode' => $c->province
                                    ],
                                    [
                                        'url' => $c->district ? 'district' : null,
                                        'valueCode' => $c->district
                                    ],
                                    [
                                        'url' => $c->village ? 'village' : null,
                                        'valueCode' => $c->village
                                    ],
                                    [
                                        'url' => $c->rw ? 'rw' : null,
                                        'valueCode' => $c->rw
                                    ],
                                    [
                                        'url' => $c->rt ? 'rt' : null,
                                        'valueCode' => $c->rt
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        return $contact;
    }


    private function createTypeArray($types): array
    {
        $type = [];

        if (!empty($types)) {
            foreach ($types as $t) {
                $type[] = [
                    'coding' => [
                        [
                            'system' => $t ? Organization::TYPE['binding']['valueset']['system'] : null,
                            'code' => $t,
                            'display' => $t ? Organization::TYPE['binding']['valueset']['display'][$t] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $type;
    }
}
