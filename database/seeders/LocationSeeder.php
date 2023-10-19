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
use Exception;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver')
                ->where('resource.res_type', '=', 'Location');
        })->get();

        foreach ($locations as $l) {
            $resContent = json_decode($l->res_text, true);
            $alias = returnAttribute($resContent, ['alias'], null);
            if (is_array($alias) || is_object($alias)) {
                $alias = implode(', ', $alias);
            }
            $address = returnAddress(returnAttribute($resContent, ['address'], null));
            $addressUse = $address['use'];
            $addressLine = $address['line'];
            unset($address['use']);
            unset($address['line']);
            $extension = getExtension($resContent);
            $identifier = returnAttribute($resContent, ['identifier'], null);
            $type = getResourceType($resContent);
            $telecom = returnAttribute($resContent, ['telecom'], null);
            $operationHours = getOperationHours($resContent);

            $loc = Location::create(
                array_merge(
                    [
                        'resource_id' => $l->resource_id,
                        'status' => returnAttribute($resContent, ['status'], 'suspended'),
                        'operational_status' => returnAttribute($resContent, ['operationalStatus', 'code'], null),
                        'name' => returnAttribute($resContent, ['name'], ''),
                        'alias' => $alias,
                        'description' => returnAttribute($resContent, ['description'], null),
                        'mode' => returnAttribute($resContent, ['mode'], null),
                        'address_use' => $addressUse,
                        'address_line' => $addressLine,
                        'physical_type' => returnAttribute($resContent, ['physicalType', 'coding', 0, 'code'], 'ro'),
                        'longitude' => returnAttribute($resContent, ['position', 'longitude'], 0),
                        'latitude' => returnAttribute($resContent, ['position', 'latitude'], 0),
                        'altitude' => returnAttribute($resContent, ['position', 'altitude'], null),
                        'managing_organization' => returnAttribute($resContent, ['managingOrganization', 'reference'], null),
                        'part_of' => returnAttribute($resContent, ['partOf', 'reference'], null),
                        'availability_exceptions' => getAvailabilityExceptions($resContent),
                        'service_class' => getServiceClass($extension)
                    ],
                    $address
                )
            );

            $foreignKey = ['location_id' => $loc->id];

            parseAndCreate(LocationIdentifier::class, $identifier, 'returnIdentifier', $foreignKey);

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

            parseAndCreate(LocationTelecom::class, $telecom, 'returnTelecom', $foreignKey);

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
