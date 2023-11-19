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
            $this->getReferenceDataRules('basedOn.*.'),
            $this->getReferenceDataRules('insurance.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->getDosageDataRules('dosage.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medicationRequest' => 'required|array',
            'identifier' => 'nullable|array',
            'category' => 'nullable|array',
            'reason' => 'nullable|array',
            'basedOn' => 'nullable|array',
            'insurance' => 'nullable|array',
            'note' => 'nullable|array',
            'dosage' => 'nullable|array'
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'medicationRequest.status' => ['required', Rule::in(MedicationRequest::STATUS_CODE)],
                'medicationRequest.status_reason' => ['nullable', Rule::in(MedicationRequest::STATUS_REASON_CODE)],
                'medicationRequest.intent' => ['required', Rule::in(MedicationRequest::INTENT_CODE)],
                'medicationRequest.priority' => ['nullable', Rule::in(MedicationRequest::PRIORITY_CODE)],
                'medicationRequest.do_not_perform' => 'nullable|boolean',
                'medicationRequest.reported' => 'nullable|boolean',
                'medicationRequest.medication' => 'required|string',
                'medicationRequest.subject' => 'required|string',
                'medicationRequest.encounter' => 'nullable|string',
                'medicationRequest.authored_on' => 'nullable|date',
                'medicationRequest.requester' => 'nullable|string',
                'medicationRequest.performer' => 'nullable|string',
                'medicationRequest.performer_type' => 'nullable|string',
                'medicationRequest.recorder' => 'nullable|string',
                'medicationRequest.course_of_therapy' => ['nullable', Rule::in(MedicationRequest::COURSE_OF_THERAPY_CODE)],
                'medicationRequest.repeats_allowed' => 'nullable|integer|gte:0',
                'medicationRequest.dispense_performer' => 'nullable|string',
                'medicationRequest.substitution_allowed' => 'nullable|array',
                'medicationRequest.substitution_allowed.allowedBoolean' => 'nullable|boolean',
                'medicationRequest.substitution_reason' => ['nullable', Rule::in(MedicationRequest::SUBSTITUTION_REASON_CODE)],
            ],
            $this->getDurationDataRules('medicationRequest.dispense_interval_'),
            $this->getPeriodDataRules('medicationRequest.validity_period_'),
            $this->getQuantityDataRules('medicationRequest.quantity_', true),
            $this->getDurationDataRules('medicationRequest.supply_duration_'),
            $this->getCodeableConceptDataRules('medicationRequest.substitution_allowed.allowedCodeableConcept.coding.*.', MedicationRequest::SUBSTITUTION_ALLOWED_CODE)
        );
    }
}
