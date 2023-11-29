<?php

namespace App\Http\Requests;

use App\Constants;
use App\Models\Organization;
use App\Models\OrganizationContact;
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
            $prefix . 'type.*' => ['required', Rule::in(Organization::TYPE_CODE)],
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
                $prefix . 'contact_data.purpose' => ['nullable', Rule::in(OrganizationContact::PURPOSE_CODE)],
                $prefix . 'contact_data.name_use' => ['nullable', Rule::in(Constants::NAME_USE_CODE)],
                $prefix . 'contact_data.name_text' => 'required|string',
                $prefix . 'contact_data.address_use' => ['nullable', Rule::in(Constants::ADDRESS_USE_CODE)],
                $prefix . 'contact_data.address_type' => ['nullable', Rule::in(Constants::ADDRESS_TYPE_CODE)],
                $prefix . 'contact_data.address_line' => 'nullable|array',
                $prefix . 'contact_data.address_line.*' => 'required|string',
                $prefix . 'contact_data.country' => 'nullable|string|exists:codesystem_iso3166,code',
                $prefix . 'contact_data.postal_code' => 'nullable|string',
                $prefix . 'contact_data.province' => 'nullable|integer|gte:0|digits:2|exists:codesystem_administrativecode,kode',
                $prefix . 'contact_data.city' => 'nullable|integer|gte:0|digits:4|exists:codesystem_administrativecode,kode',
                $prefix . 'contact_data.district' => 'nullable|integer|gte:0|digits:6|exists:codesystem_administrativecode,kode',
                $prefix . 'contact_data.village' => 'nullable|integer|gte:0|digits:10|exists:codesystem_administrativecode,kode',
                $prefix . 'contact_data.rw' => 'nullable|integer|gte:0|max_digits:2',
                $prefix . 'contact_data.rt' => 'nullable|integer|gte:0|max_digits:2'
            ],
            $this->getTelecomDataRules($prefix . 'telecom.*.')
        );
    }
}
