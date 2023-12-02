<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
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
            $this->baseAttributeRules(),
            $this->baseDataRules('location.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getTelecomDataRules('telecom.*.'),
            $this->operationHoursDataRules('operationHours.*.')
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'location' => 'required|array',
            'identifier' => 'nullable|array',
            'telecom' => 'nullable|array',
            'operationHours' => 'nullable|array'
        ];
    }


    private function baseDataRules($prefix): array
    {
        return [
            $prefix . 'status' => ['nullable', Rule::in(Location::STATUS_CODE)],
            $prefix . 'operational_status' => ['nullable', Rule::in(Location::OPERATIONAL_STATUS_CODE)],
            $prefix . 'name' => 'nullable|string',
            $prefix . 'alias' => 'nullable|array',
            $prefix . 'alias.*' => 'required|string',
            $prefix . 'description' => 'nullable|string',
            $prefix . 'mode' => ['nullable', Rule::in(Location::MODE_CODE)],
            $prefix . 'type' => 'nullable|array',
            $prefix . 'type.*' => ['nullable', Rule::in(Location::TYPE_CODE)],
            $prefix . 'address_use' => ['nullable', Rule::in(Constants::ADDRESS_USE_CODE)],
            $prefix . 'address_line' => 'nullable|array',
            $prefix . 'address_line.*' => 'required|string',
            $prefix . 'country' => 'nullable|string|exists:codesystem_iso3166,code',
            $prefix . 'postal_code' => 'nullable|string',
            $prefix . 'province' => 'nullable|integer|exists:codesystem_administrativecode,kode',
            $prefix . 'city' => 'nullable|integer|exists:codesystem_administrativecode,kode',
            $prefix . 'district' => 'nullable|integer|exists:codesystem_administrativecode,kode',
            $prefix . 'village' => 'nullable|integer|exists:codesystem_administrativecode,kode',
            $prefix . 'rw' => 'nullable|integer',
            $prefix . 'rt' => 'nullable|integer',
            $prefix . 'physical_type' => ['nullable', Rule::in(Location::PHYSICAL_TYPE_CODE)],
            $prefix . 'longitude' => 'nullable|numeric',
            $prefix . 'latitude' => 'nullable|numeric',
            $prefix . 'altitude' => 'nullable|numeric',
            $prefix . 'managing_organization' => 'nullable|string',
            $prefix . 'part_of' => 'nullable|string',
            $prefix . 'availability_exceptions' => 'nullable|string',
            $prefix . 'endpoint' => 'nullable|array',
            $prefix . 'endpoint.*' => 'required|string',
            $prefix . 'service_class' => ['nullable', Rule::in(Location::SERVICE_CLASS_CODE)],
        ];
    }


    private function operationHoursDataRules($prefix): array
    {
        return [
            $prefix . 'days_of_week' => 'nullable|array',
            $prefix . 'days_of_week.*' => ['required', Rule::in(Constants::DAYS_OF_WEEK)],
            $prefix . 'all_day' => 'nullable|boolean',
            $prefix . 'opening_time' => 'nullable|date_format:H:i:s',
            $prefix . 'closing_time' => 'nullable|date_format:H:i:s',
        ];
    }
}
