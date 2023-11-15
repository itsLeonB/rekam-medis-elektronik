<?php

namespace App\Http\Requests;

use App\Models\MedicationRequest;
use App\Models\MedicationRequestCategory;
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
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getCodeableConceptDataRules('category.*.', MedicationRequestCategory::CODE),
            $this->getCodeableConceptDataRules('reason.*.'),
            $this->getReferenceDataRules('reason.*.', true),
            $this->getReferenceDataRules('based_on.*.'),
            $this->getReferenceDataRules('insurance.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->getDosageDataRules('dosage.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medication_request' => 'required|array',
            'identifier' => 'nullable|array',
            'category' => 'nullable|array',
            'reason' => 'nullable|array',
            'based_on' => 'nullable|array',
            'insurance' => 'nullable|array',
            'note' => 'nullable|array',
            'dosage' => 'nullable|array'
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'medication_request.status' => ['required', Rule::in(MedicationRequest::STATUS_CODE)],
                'medication_request.status_reason' => ['nullable', Rule::in(MedicationRequest::STATUS_REASON_CODE)],
                'medication_request.intent' => ['required', Rule::in(MedicationRequest::INTENT_CODE)],
                'medication_request.priority' => ['nullable', Rule::in(MedicationRequest::PRIORITY_CODE)],
                'medication_request.do_not_perform' => 'nullable|boolean',
                'medication_request.reported' => 'nullable|boolean',
                'medication_request.medication' => 'required|string',
                'medication_request.subject' => 'required|string',
                'medication_request.encounter' => 'nullable|string',
                'medication_request.authored_on' => 'nullable|date',
                'medication_request.requester' => 'nullable|string',
                'medication_request.performer' => 'nullable|string',
                'medication_request.performer_type' => 'nullable|string',
                'medication_request.recorder' => 'nullable|string',
                'medication_request.course_of_therapy' => ['nullable', Rule::in(MedicationRequest::COURSE_OF_THERAPY_CODE)],
                'medication_request.repeats_allowed' => 'nullable|integer|gte:0',
                'medication_request.dispense_performer' => 'nullable|string',
                'medication_request.substitution_allowed' => 'nullable|array',
                'medication_request.substitution_allowed.allowedBoolean' => 'nullable|boolean',
                'medication_request.substitution_reason' => ['nullable', Rule::in(MedicationRequest::SUBSTITUTION_REASON_CODE)],
            ],
            $this->getDurationDataRules('medication_request.dispense_interval_'),
            $this->getPeriodDataRules('medication_request.validity_period_'),
            $this->getQuantityDataRules('medication_request.quantity_', true),
            $this->getDurationDataRules('medication_request.supply_duration_'),
            $this->getCodeableConceptDataRules('medication_request.substitution_allowed.allowedCodeableConcept.coding.*.', MedicationRequest::SUBSTITUTION_ALLOWED_CODE)
        );
    }
}
