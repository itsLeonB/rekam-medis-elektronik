<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\BackboneElements\PatientContact;
use App\Models\Fhir\BackboneElements\PatientLink;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Validation\Rule;

class PatientRequest extends FhirRequest
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
                'identifier' => 'required|array',
                'active' => 'nullable|boolean',
                'name' => 'nullable|array',
                'telecom' => 'nullable|array',
                'gender' => ['nullable', Rule::in(Patient::GENDER['binding']['valueset'])],
                'birthDate' => 'nullable|date',
                'deceasedBoolean' => 'nullable|boolean',
                'deceasedDateTime' => 'nullable|date',
                'address' => 'nullable|array',
                'maritalStatus' => 'nullable|array',
                'multipleBirthBoolean' => 'sometimes|boolean',
                'multipleBirthInteger' => 'sometimes|integer',
                'photo' => 'nullable|array',
                'contact' => 'nullable|array',
                'contact.*.relationship' => 'nullable|array',
                'contact.*.name' => 'nullable|array',
                'contact.*.telecom' => 'nullable|array',
                'contact.*.address' => 'nullable|array',
                'contact.*.gender' => ['nullable', Rule::in(PatientContact::GENDER['binding']['valueset'])],
                'contact.*.organization' => 'nullable|array',
                'contact.*.period' => 'nullable|array',
                'communication' => 'nullable|array',
                'communication.*.language' => 'sometimes|array',
                'communication.*.preferred' => 'nullable|boolean',
                'generalPractitioner' => 'nullable|array',
                'managingOrganization' => 'nullable|array',
                'link' => 'nullable|array',
                'link.*.other' => 'sometimes|array',
                'link.*.type' => ['sometimes', Rule::in(PatientLink::TYPE['binding']['valueset']['code'])],
                'extension' => 'nullable|array',
                '_birthDate' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getHumanNameRules('name.*.'),
            $this->getContactPointRules('telecom.*.'),
            $this->getAddressRules('address.*.'),
            $this->getCodeableConceptRules('maritalStatus.'),
            $this->getAttachmentRules('photo.*.'),
            $this->getCodeableConceptRules('contact.*.relationship.*.'),
            $this->getHumanNameRules('contact.*.name.'),
            $this->getContactPointRules('contact.*.telecom.*.'),
            $this->getAddressRules('contact.*.address.'),
            $this->getReferenceRules('contact.*.organization.'),
            $this->getPeriodRules('contact.*.period.'),
            $this->getCodeableConceptRules('communication.*.language.'),
            $this->getReferenceRules('generalPractitioner.*.'),
            $this->getReferenceRules('managingOrganization.'),
            $this->getReferenceRules('link.*.other.'),
        );
    }
}
