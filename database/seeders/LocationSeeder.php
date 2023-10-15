<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\LocationIdentifier;
use App\Models\LocationOperationHours;
use App\Models\LocationTelecom;
use App\Models\LocationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Resource::join('resource_content', function ($join) {
            $join->on('resource.res_id', '=', 'resource_content.res_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Location');
        })->get();

        foreach ($locations as $l) {
            $resContent = json_decode($l->res_text, true);
            $address = getAddress($resContent);
            $addressDetails = getAddressDetails($address);
            $position = getPosition($resContent);
            $extension = getExtension($resContent);
            $identifier = getIdentifier($resContent);
            $type = getResourceType($resContent);
            $telecom = getTelecom($resContent);
            $operationHours = getOperationHours($resContent);

            $loc = Location::create(
                [
                    'res_id' => $l->res_id,
                    'active' => getActive($resContent),
                    'operational_status' => getOperationalStatus($resContent),
                    'name' => getName($resContent),
                    'alias' => getAlias($resContent),
                    'description' => getDescription($resContent),
                    'mode' => getMode($resContent),
                    'address_use' => $addressDetails['use'],
                    'address_line' => $addressDetails['line'],
                    'country' => $addressDetails['country'],
                    'postal_code' => $addressDetails['postalCode'],
                    'province' => $addressDetails['province'],
                    'city' => $addressDetails['city'],
                    'district' => $addressDetails['district'],
                    'village' => $addressDetails['village'],
                    'rw' => $addressDetails['rw'],
                    'rt' => $addressDetails['rt'],
                    'physical_type' => getPhysicalType($resContent),
                    'longitude' => $position['longitude'],
                    'latitude' => $position['latitude'],
                    'altitude' => $position['altitude'],
                    'managing_organization' => getManagingOrganization($resContent),
                    'part_of' => getPartOf($resContent),
                    'availability_exceptions' => getAvailabilityExceptions($resContent),
                    'service_class' => getServiceClass($extension)
                ]
            );

            if (is_array($identifier) || is_object($identifier)) {
                foreach ($identifier as $i) {
                    $identifierDetails = parseIdentifier($i);
                    LocationIdentifier::create(
                        [
                            'location_id' => $loc->id,
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
                    LocationType::create(
                        [
                            'location_id' => $loc->id,
                            'system' => $typeDetails['system'],
                            'code' => $typeDetails['code'],
                            'display' => $typeDetails['display']
                        ]
                    );
                }
            }

            if (is_array($telecom) || is_object($telecom)) {
                foreach ($telecom as $t) {
                    $telecomDetails = getTelecomDetails($t);
                    LocationTelecom::create(
                        [
                            'location_id' => $loc->id,
                            'system' => $telecomDetails['system'],
                            'use' => $telecomDetails['use'],
                            'value' => $telecomDetails['value']
                        ]
                    );
                }
            }

            if (is_array($operationHours) || is_object($operationHours)) {
                foreach ($operationHours as $o) {
                    $operationHoursDetails = parseOperationHours($o);
                    LocationOperationHours::create(
                        [
                            'location_id' => $loc->id,
                            'mon' => $operationHoursDetails['mon'],
                            'tue' => $operationHoursDetails['tue'],
                            'wed' => $operationHoursDetails['wed'],
                            'thu' => $operationHoursDetails['thu'],
                            'fri' => $operationHoursDetails['fri'],
                            'sat' => $operationHoursDetails['sat'],
                            'sun' => $operationHoursDetails['sun'],
                            'opening_time' => $operationHoursDetails['openingTime'],
                            'closing_time' => $operationHoursDetails['closingTime']
                        ]
                    );
                }
            }
        }
    }
}
