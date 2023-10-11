<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationIdentifier;
use App\Models\OrganizationTelecom;
use App\Models\OrganizationType;
use App\Models\OrganizationAddress;
use App\Models\OrganizationContact;
use App\Models\OrganizationContactTelecom;
use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Resource::join('resource_content', function ($join) {
            $join->on('resource.res_id', '=', 'resource_content.res_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Organization');
        })->get();

        foreach ($organizations as $o) {
            $resContent = json_decode($o->res_text, true);
            $active = getActive($resContent);
            $identifier = getIdentifier($resContent);
            $type = getOrganizationType($resContent);
            $telecoms = getTelecom($resContent);
            $address = getAddress($resContent);
            $contact = getContact($resContent);

            $org = Organization::create(
                [
                    'res_id' => $o->res_id,
                    'active' => $active,
                    'name' => $resContent['name'],
                    'alias' => getActive($resContent),
                    'part_of' => getPartOf($resContent)
                ]
            );

            if (is_array($identifier) || is_object($identifier)) {
                foreach ($identifier as $i) {
                    $identifierDetails = parseIdentifier($i);
                    OrganizationIdentifier::create(
                        [
                            'organization_id' => $org->id,
                            'system' => $identifierDetails['system'],
                            'use' => $identifierDetails['use'],
                            'value' => $identifierDetails['value']
                        ]
                    );
                }
            }

            if (is_array($type) || is_object($type)) {
                foreach ($type as $t) {
                    $typeDetails = getTypeDetails($t);
                    OrganizationType::create(
                        [
                            'organization_id' => $org->id,
                            'system' => $typeDetails['system'],
                            'code' => $typeDetails['code'],
                            'display' => $typeDetails['display']
                        ]
                    );
                }
            }

            if (is_array($telecoms) || is_object($telecoms)) {
                foreach ($telecoms as $telecom) {
                    $telecomDetails = getTelecomDetails($telecom);
                    OrganizationTelecom::create(
                        [
                            'organization_id' => $org->id,
                            'system' => $telecomDetails['system'],
                            'use' => $telecomDetails['use'],
                            'value' => $telecomDetails['value']
                        ]
                    );
                }
            }

            if (is_array($address) || is_object($address)) {
                foreach ($address as $a) {
                    $addressDetails = getAddressDetails($a);
                    OrganizationAddress::create(
                        [
                            'organization_id' => $org->id,
                            'use' => $addressDetails['use'],
                            'line' => $addressDetails['line'],
                            'country' => $addressDetails['country'],
                            'postal_code' => $addressDetails['postalCode'],
                            'province' => $addressDetails['province'],
                            'city' => $addressDetails['city'],
                            'district' => $addressDetails['district'],
                            'village' => $addressDetails['village'],
                            'rw' => $addressDetails['rw'],
                            'rt' => $addressDetails['rt']
                        ]
                    );
                }
            }

            if (is_array($contact) || is_object($contact)) {
                foreach ($contact as $c) {
                    $contactDetails = getOrganizationContactDetails($c);
                    $addressDetails = getAddressDetails($contactDetails['address']);
                    $organizationContact = OrganizationContact::create(
                        [
                            'organization_id' => $org->id,
                            'purpose_system' => $contactDetails['purposeSystem'],
                            'purpose_code' => $contactDetails['purposeCode'],
                            'purpose_display' => $contactDetails['purposeDisplay'],
                            'name' => $contactDetails['name'],
                            'address_use' => $addressDetails['use'],
                            'address_line' => $addressDetails['line'],
                            'country' => $addressDetails['country'],
                            'postal_code' => $addressDetails['postalCode'],
                            'province' => $addressDetails['province'],
                            'city' => $addressDetails['city'],
                            'district' => $addressDetails['district'],
                            'village' => $addressDetails['village'],
                            'rw' => $addressDetails['rw'],
                            'rt' => $addressDetails['rt']
                        ]
                    );

                    if (is_array($contactDetails['telecom']) || is_object($contactDetails['telecom'])) {
                        foreach ($contactDetails['telecom'] as $telecom) {
                            $contactTelecomDetails = getTelecomDetails($telecom);
                            OrganizationContactTelecom::create(
                                [
                                    'organization_contact_id' => $organizationContact->id,
                                    'system' => $contactTelecomDetails['system'],
                                    'use' => $contactTelecomDetails['use'],
                                    'value' => $contactTelecomDetails['value']
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
