<?php

namespace App\Http\Resources;

use App\Models\Codesystems\AdministrativeCode;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $location = $this->getData('location');

        $data = $this->resourceStructure($location);

        $data = removeEmptyValues($data);

        return $data;
    }


    private function resourceStructure($location): array
    {
        return [
            'resourceType' => 'Location',
            'id' => $this->satusehat_id,
            'identifier' => $this->createIdentifierArray($location->identifier),
            'status' => $location->status,
            'operationalStatus' => [
                'system' => $location->operational_status ? Location::OPERATIONAL_STATUS_SYSTEM : null,
                'code' => $location->operational_status,
                'display' => $location->operational_status ? Location::OPERATIONAL_STATUS_DISPLAY[$location->operational_status] ?? null : null
            ],
            'name' => $location->name,
            'alias' => $location->alias,
            'description' => $location->description,
            'mode' => $location->mode,
            'type' => $this->createTypeArray($location->type),
            'telecom' => $this->createTelecomArray($location->telecom),
            'address' => [
                'use' => $location->address_use,
                'line' => $location->address_line,
                'country' => $location->country,
                'postalCode' => $location->postal_code,
                'city' => $location->city ? AdministrativeCode::where('kode', $location->city)->first()->nama ?? null : null,
                'extension' => [
                    [
                        'url' => AdministrativeCode::URL,
                        'extension' => [
                            [
                                'url' => $location->province ? 'province' : null,
                                'valueCode' => $location->province
                            ],
                            [
                                'url' => $location->city ? 'city' : null,
                                'valueCode' => $location->city
                            ],
                            [
                                'url' => $location->district ? 'district' : null,
                                'valueCode' => $location->district
                            ],
                            [
                                'url' => $location->village ? 'village' : null,
                                'valueCode' => $location->village
                            ],
                            [
                                'url' => $location->rt ? 'rt' : null,
                                'valueCode' => $location->rt
                            ],
                            [
                                'url' => $location->rw ? 'rw' : null,
                                'valueCode' => $location->rw
                            ],
                        ]
                    ]
                ]
            ],
            'physicalType' => [
                'coding' => [
                    [
                        'system' => $location->physical_type ? Location::PHYSICAL_TYPE_SYSTEM[$location->physical_type] : null,
                        'code' => $location->physical_type,
                        'display' => $location->physical_type ? Location::PHYSICAL_TYPE_DISPLAY[$location->physical_type] : null
                    ]
                ]
            ],
            'position' => [
                'longitude' => $location->longitude,
                'latitude' => $location->latitude,
                'altitude' => $location->altitude
            ],
            'managingOrganization' => [
                'reference' => $location->managing_organization
            ],
            'partOf' => [
                'reference' => $location->part_of
            ],
            'hoursOfOperation' => $this->createOperationHoursArray($location->operationHours),
            'availabilityExceptions' => $location->availability_exceptions,
            'endpoint' => $this->referenceArray($location->endpoint),
            'extension' => [
                [
                    'url' => $location->service_class ? Location::SERVICE_CLASS_URL : null,
                    'valueCodeableConcept' => [
                        'coding' => [
                            [
                                'system' => $location->service_class ? Location::SERVICE_CLASS_SYSTEM[$location->service_class] ?? null : null,
                                'code' => $location->service_class,
                                'display' => $location->service_class ? Location::SERVICE_CLASS_DISPLAY[$location->service_class] ?? null : null
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }


    private function referenceArray($references): array
    {
        $reference = [];

        if (!empty($references)) {
            foreach ($references as $r) {
                $reference[] = [
                    'reference' => $r
                ];
            }
        }

        return $reference;
    }


    private function createOperationHoursArray($operationHours): array
    {
        $hours = [];

        if (!empty($operationHours)) {
            foreach ($operationHours as $o) {
                $hours[] = [
                    'daysOfWeek' => $o->days_of_week,
                    'allDay' => $o->all_day,
                    'openingTime' => $o->opening_time,
                    'closingTime' => $o->closing_time
                ];
            }
        }

        return $hours;
    }


    private function createTypeArray($types): array
    {
        $type = [];

        if (!empty($types)) {
            foreach ($types as $t) {
                $type[] = [
                    'coding' => [
                        [
                            'system' => $t ? Location::TYPE_SYSTEM : null,
                            'code' => $t,
                            'display' => $t ? Location::TYPE_DISPLAY[$t] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $type;
    }
}
