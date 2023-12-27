<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;

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
            [
                'identifier' => 'nullable|array',
                'active' => 'nullable|boolean',
                'type' => 'nullable|array',
                'name' => 'nullable|string',
                'alias' => 'nullable|array',
                'alias.*' => 'sometimes|string',
                'telecom' => 'nullable|array',
                'address' => 'nullable|array',
                'part_of' => 'nullable|array',
                'contact' => 'nullable|array',
                'contact.*.purpose' => 'nullable|array',
                'contact.*.name' => 'nullable|array',
                'contact.*.telecom' => 'nullable|array',
                'contact.*.address' => 'nullable|array',
                'endpoint' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodeableConceptRules('type.*.'),
            $this->getContactPointRules('telecom.*.'),
            $this->getAddressRules('address.*.'),
            $this->getReferenceRules('part_of.'),
            $this->getCodeableConceptRules('contact.*.purpose.'),
            $this->getHumanNameRules('contact.*.name.'),
            $this->getContactPointRules('contact.*.telecom.*.'),
            $this->getAddressRules('contact.*.address.'),
            $this->getReferenceRules('endpoint.*.')
        );
    }
}
