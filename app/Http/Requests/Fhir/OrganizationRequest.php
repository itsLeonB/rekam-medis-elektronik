<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\{
    Organization,
    OrganizationContact
};
use Illuminate\Validation\Rule;

class OrganizationRequest extends FhirRequest
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
            $this->baseDataRules('organization.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getTelecomDataRules('telecom.*.'),
            $this->getAddressDataRules('address.*.'),
            $this->contactDataRules('contact.*.')
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'organization' => 'required|array',
            'identifier' => 'nullable|array',
            'telecom' => 'nullable|array',
            'address' => 'nullable|array',
            'contact' => 'nullable|array'
        ];
    }


    private function baseDataRules($prefix): array
    {
        return [
            $prefix . 'active' => 'nullable|boolean',
            $prefix . 'type' => 'nullable|array',
            $prefix . 'type.*' => ['required', Rule::in(Organization::TYPE['binding']['valueset']['code'])],
            $prefix . 'name' => 'nullable|string',
            $prefix . 'alias' => 'nullable|array',
            $prefix . 'alias.*' => 'required|string',
            $prefix . 'part_of' => 'nullable|string',
            $prefix . 'endpoint' => 'nullable|array',
            $prefix . 'endpoint.*' => 'required|string'
        ];
    }


    private function contactDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'contact_data.purpose' => ['nullable', Rule::in(OrganizationContact::PURPOSE['binding']['valueset']['code'])],
                $prefix . 'contact_data.name_text' => 'nullable|string',
                $prefix . 'contact_data.name_family' => 'nullable|string',
                $prefix . 'contact_data.name_given' => 'nullable|array',
                $prefix . 'contact_data.name_given.*' => 'nullable|string',
                $prefix . 'contact_data.name_prefix' => 'nullable|array',
                $prefix . 'contact_data.name_prefix.*' => 'nullable|string',
                $prefix . 'contact_data.name_suffix' => 'nullable|array',
                $prefix . 'contact_data.name_suffix.*' => 'nullable|string',
                $prefix . 'contact_data.address_use' => ['nullable', Rule::in(OrganizationContact::ADDRESS_USE['binding']['valueset']['code'])],
                $prefix . 'contact_data.address_type' => ['nullable', Rule::in(OrganizationContact::ADDRESS_TYPE['binding']['valueset']['code'])],
                $prefix . 'contact_data.address_line' => 'nullable|array',
                $prefix . 'contact_data.address_line.*' => 'nullable|string',
                $prefix . 'contact_data.country' => 'nullable|string|exists:codesystem_iso3166,code',
                $prefix . 'contact_data.postal_code' => 'nullable|string',
                $prefix . 'contact_data.province' => ['nullable', Rule::exists(OrganizationContact::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_provinsi')],
                $prefix . 'contact_data.city' => ['nullable', Rule::exists(OrganizationContact::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_kabko')],
                $prefix . 'contact_data.district' => ['nullable', Rule::exists(OrganizationContact::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_kecamatan')],
                $prefix . 'contact_data.village' => ['nullable', Rule::exists(OrganizationContact::ADMINISTRATIVE_CODE['binding']['valueset']['table'], 'kode_kelurahan')],
                $prefix . 'contact_data.rw' => 'nullable|integer|gte:0|max_digits:2',
                $prefix . 'contact_data.rt' => 'nullable|integer|gte:0|max_digits:2'
            ],
            $this->getTelecomDataRules($prefix . 'telecom.*.')
        );
    }
}
