<?php

namespace App\Http\Resources;

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

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($location): array
    {
        return [
            'resourceType' => 'Location',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($location->identifier, 'createIdentifierResource'),
            'status' => $location->status,
            'operationalStatus' => $this->createCodingResource($location->operationalStatus),
            'name' => $location->name,
            'alias' => $location->alias,
            'description' => $location->description,
            'mode' => $location->mode,
            'type' => $this->createMany($location->type, 'createCodeableConceptResource'),
            'telecom' => $this->createMany($location->telecom, 'createContactPointResource'),
            'address' => $this->createAddressResource($location->address),
            'physicalType' => $this->createCodeableConceptResource($location->physicalType),
            'position' => $this->createPositionResource($location->position),
            'managingOrganization' => $this->createReferenceResource($location->managingOrganization),
            'partOf' => $this->createReferenceResource($location->partOf),
            'hoursOfOperation' => $this->createMany($location->hoursOfOperation, 'createHoursOfOperationResource'),
            'availabilityExceptions' => $location->availability_exceptions,
            'endpoint' => $this->createMany($location->endpoint, 'createReferenceResource'),
            'extension' => [
                $this->createExtensionResource($location->serviceClass)
            ]
        ];
    }

    public function createHoursOfOperationResource($hoursOfOperation)
    {
        if (!empty($hoursOfOperation)) {
            return [
                'daysOfWeek' => $hoursOfOperation->days_of_week,
                'allDay' => $hoursOfOperation->all_day,
                'openingTime' => $hoursOfOperation->opening_time,
                'closingTime' => $hoursOfOperation->closing_time
            ];
        } else {
            return null;
        }
    }

    public function createPositionResource($position)
    {
        if (!empty($position)) {
            return [
                'longitude' => $position->longitude,
                'latitude' => $position->latitude,
                'altitude' => $position->altitude
            ];
        } else {
            return null;
        }
    }
}
