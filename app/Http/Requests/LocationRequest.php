<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Location;
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
            $prefix . 'status' => ['nullable', Rule::in(Location::STATUS['binding']['valueset']['code'])],
            $prefix . 'operational_status' => ['nullable', Rule::in(Location::OPERATIONAL_STATUS['binding']['valueset']['code'])],
            $prefix . 'name' => 'nullable|string',
            $prefix . 'alias' => 'nullable|array',
            $prefix . 'alias.*' => 'required|string',
            $prefix . 'description' => 'nullable|string',
            $prefix . 'mode' => ['nullable', Rule::in(Location::MODE['binding']['valueset']['code'])],
            $prefix . 'type' => 'nullable|array',
            $prefix . 'type.*' => ['nullable', Rule::in(Location::TYPE['binding']['valueset']['code'])],
            $prefix . 'address_use' => ['nullable', Rule::in(Location::ADDRESS_USE['binding']['valueset']['code'])],
            $prefix . 'address_type' => ['nullable', Rule::in(Location::ADDRESS_TYPE['binding']['valueset']['code'])],
            $prefix . 'address_line' => 'nullable|array',
            $prefix . 'address_line.*' => 'required|string',
            $prefix . 'country' => ['nullable', Rule::exists(Location::COUNTRY['binding']['valueset']['table'], 'code')],
            $prefix . 'postal_code' => 'nullable|string',
            $prefix . 'province' => ['nullable', Rule::exists(Location::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_provinsi')],
            $prefix . 'city' => ['nullable', Rule::exists(Location::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_kabko')],
            $prefix . 'district' => ['nullable', Rule::exists(Location::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_kecamatan')],
            $prefix . 'village' => ['nullable', Rule::exists(Location::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_kelurahan')],
            $prefix . 'rw' => 'nullable|integer',
            $prefix . 'rt' => 'nullable|integer',
            $prefix . 'physical_type' => ['nullable', Rule::in(Location::PHYSICAL_TYPE['binding']['valueset']['code'])],
            $prefix . 'longitude' => 'nullable|numeric',
            $prefix . 'latitude' => 'nullable|numeric',
            $prefix . 'altitude' => 'nullable|numeric',
            $prefix . 'managing_organization' => 'nullable|string',
            $prefix . 'part_of' => 'nullable|string',
            $prefix . 'availability_exceptions' => 'nullable|string',
            $prefix . 'endpoint' => 'nullable|array',
            $prefix . 'endpoint.*' => 'required|string',
            $prefix . 'service_class' => ['nullable', Rule::in(Location::SERVICE_CLASS['binding']['valueset']['code'])],
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
