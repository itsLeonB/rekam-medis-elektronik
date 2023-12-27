<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\BackboneElements\LocationHoursOfOperation;
use App\Models\Fhir\Resources\Location;
use Illuminate\Validation\Rule;

class LocationRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(
            [
                'identifier' => 'nullable|array',
                'status' => ['nullable', Rule::in(Location::STATUS['binding']['valueset']['code'])],
                'operationalStatus' => 'nullable|array',
                'name' => 'nullable|string',
                'alias' => 'nullable|array',
                'alias.*' => 'sometimes|string',
                'description' => 'nullable|string',
                'mode' => ['nullable', Rule::in(Location::MODE['binding']['valueset']['code'])],
                'type' => 'nullable|array',
                'telecom' => 'nullable|array',
                'address' => 'nullable|array',
                'physicalType' => 'nullable|array',
                'position' => 'nullable|array',
                'position.longitude' => 'sometimes|numeric',
                'position.latitude' => 'sometimes|numeric',
                'position.altitude' => 'nullable|numeric',
                'managingOrganization' => 'nullable|array',
                'partOf' => 'nullable|array',
                'hoursOfOperation' => 'nullable|array',
                'hoursOfOperation.*.daysOfWeek' => 'nullable|array',
                'hoursOfOperation.*.daysOfWeek.*' => ['sometimes', Rule::in(LocationHoursOfOperation::DAYS_OF_WEEK['binding']['valueset']['code'])],
                'hoursOfOperation.*.allDay' => 'nullable|boolean',
                'hoursOfOperation.*.openingTime' => 'nullable|date_format:H:i:s',
                'hoursOfOperation.*.closingTime' => 'nullable|date_format:H:i:s',
                'availabilityExceptions' => 'nullable|string',
                'endpoint' => 'nullable|array',
                'extension' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodingRules('operationalStatus.'),
            $this->getCodeableConceptRules('type.*.'),
            $this->getContactPointRules('telecom.*.'),
            $this->getAddressRules('address.'),
            $this->getCodeableConceptRules('physicalType.'),
            $this->getReferenceRules('managingOrganization.'),
            $this->getReferenceRules('partOf.'),
            $this->getReferenceRules('endpoint.*.'),
            $this->getExtensionRules('extension.*.')
        );
    }
}
