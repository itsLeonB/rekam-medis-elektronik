<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;

class PractitionerRequest extends FhirRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            [
                'identifier' => 'nullable|array',
                'active' => 'nullable|boolean',
                'name' => 'required|array',
                'telecom' => 'nullable|array',
                'address' => 'nullable|array',
                'gender' => 'nullable|string',
                'birthDate' => 'nullable|date',
                'photo' => 'nullable|array',
                'qualification' => 'nullable|array',
                'qualification.*.identifier' => 'nullable|array',
                'qualification.*.code' => 'sometimes|array',
                'qualification.*.period' => 'nullable|array',
                'qualification.*.issuer' => 'nullable|array',
                'communication' => 'nullable|array'
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getHumanNameRules('name.*.'),
            $this->getContactPointRules('telecom.*.'),
            $this->getAddressRules('address.*.'),
            $this->getAttachmentRules('photo.*.'),
            $this->getIdentifierRules('qualification.*.identifier.*.'),
            $this->getCodeableConceptRules('qualification.*.code.'),
            $this->getPeriodRules('qualification.*.period.'),
            $this->getIdentifierRules('qualification.*.issuer.'),
            $this->getCodeableConceptRules('communication.*.')
        );
    }
}
