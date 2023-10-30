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
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Organization');
        })->get();

        foreach ($organizations as $o) {
            $resContent = json_decode($o->res_text, true);
            $active = returnAttribute($resContent, ['active'], false);
            $identifier = returnAttribute($resContent, ['identifier'], null);
            $type = getResourceType($resContent);
            $telecoms = getTelecom($resContent);
            $address = returnAttribute($resContent, ['address'], null);
            $contact = returnAttribute($resContent, ['contact'], null);

            $org = Organization::create(
                [
                    'resource_id' => $o->resource_id,
                    'active' => $active,
                    'name' => $resContent['name'],
                    'alias' => returnAttribute($resContent, ['alias']),
                    'part_of' => getPartOf($resContent)
                ]
            );

            $foreignKey = ['organization_id' => $org->id];

            parseAndCreate(OrganizationIdentifier::class, $identifier, 'returnIdentifier', $foreignKey);

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

            parseAndCreate(OrganizationAddress::class, $address, 'returnAddress', $foreignKey);

            if (is_array($contact) || is_object($contact)) {
                foreach ($contact as $c) {
                    $contactDetails = returnOrganizationContact($c);
                    $orgContact = OrganizationContact::create(array_merge($contactDetails, $foreignKey));
                    $contactTelecom = returnAttribute($c, ['telecom'], null);
                    $orgContactFk = ['organization_contact_id' => $orgContact->id];
                    parseAndCreate(OrganizationContactTelecom::class, $contactTelecom, 'returnTelecom', $orgContactFk);
                }
            }
        }
    }
}
