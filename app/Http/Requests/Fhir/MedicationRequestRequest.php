<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\MedicationRequest;
use Illuminate\Validation\Rule;

class MedicationRequestRequest extends FhirRequest
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
            $this->baseDataRules('medicationRequest.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->getDosageDataRules('dosage.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medicationRequest' => 'required|array',
            'identifier' => 'nullable|array',
            'note' => 'nullable|array',
            'dosage' => 'nullable|array'
        ];
    }

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'status' => ['required', Rule::in(MedicationRequest::STATUS['binding']['valueset']['code'])],
                $prefix . 'status_reason' => ['nullable', Rule::in(MedicationRequest::STATUS_REASON['binding']['valueset']['code'])],
                $prefix . 'intent' => ['required', Rule::in(MedicationRequest::INTENT['binding']['valueset']['code'])],
                $prefix . 'category' => 'nullable|array',
                $prefix . 'category.*' => ['required', Rule::in(MedicationRequest::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'priority' => ['nullable', Rule::in(MedicationRequest::PRIORITY['binding']['valueset']['code'])],
                $prefix . 'do_not_perform' => 'nullable|boolean',
                $prefix . 'reported' => 'nullable|boolean',
                $prefix . 'medication' => 'required|string',
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'nullable|string',
                $prefix . 'supporting_information' => 'nullable|array',
                $prefix . 'supporting_information.*' => 'required|string',
                $prefix . 'authored_on' => 'nullable|date',
                $prefix . 'requester' => 'nullable|string',
                $prefix . 'performer' => 'nullable|string',
                $prefix . 'performer_type' => 'nullable|string',
                $prefix . 'recorder' => 'nullable|string',
                $prefix . 'reason_code' => 'nullable|array',
                $prefix . 'reason_code.*' => ['required', Rule::exists(MedicationRequest::REASON_CODE['binding']['valueset']['table'], 'code')],
                $prefix . 'reason_reference' => 'nullable|array',
                $prefix . 'reason_reference.*' => 'required|string',
                $prefix . 'based_on' => 'nullable|array',
                $prefix . 'based_on.*' => 'required|string',
                $prefix . 'course_of_therapy' => ['nullable', Rule::in(MedicationRequest::COURSE_OF_THERAPY_TYPE['binding']['valueset']['code'])],
                $prefix . 'insurance' => 'nullable|array',
                $prefix . 'insurance.*' => 'required|string',
                $prefix . 'repeats_allowed' => 'nullable|integer|gte:0',
                $prefix . 'dispense_performer' => 'nullable|string',
                $prefix . 'substitution_allowed' => 'nullable|array',
                $prefix . 'substitution_allowed.allowedBoolean' => 'nullable|boolean',
                $prefix . 'substitution_allowed.allowedCodeableConcept' => 'nullable|array',
                $prefix . 'substitution_reason' => ['nullable', Rule::in(MedicationRequest::SUBSTITUTION_REASON['binding']['valueset']['code'])],
            ],
            $this->getDurationDataRules($prefix . 'dispense_interval_'),
            $this->getPeriodDataRules($prefix . 'validity_period_'),
            $this->getQuantityDataRules($prefix . 'quantity_', true),
            $this->getDurationDataRules($prefix . 'supply_duration_'),
            $this->getCodeableConceptDataRules($prefix . 'substitution_allowed.allowedCodeableConcept.coding.*.', MedicationRequest::SUBSTITUTION_ALLOWED['binding']['valueset']['code']),
        );
    }
}
