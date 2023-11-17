<?php

namespace App\Http\Requests;

use App\Models\ServiceRequest;
use App\Models\ServiceRequestCategory;
use App\Models\ServiceRequestOrderDetail;
use Illuminate\Validation\Rule;

class ServiceRequestRequest extends FhirRequest
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
            $this->baseDataRules('service_request.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getReferenceDataRules('based_on.*.'),
            $this->getReferenceDataRules('replaces.*.'),
            $this->getCodeableConceptDataRules('category.*.', ServiceRequestCategory::CODE),
            $this->getCodeableConceptDataRules('order_detail.*.', ServiceRequestOrderDetail::CODE),
            $this->getReferenceDataRules('performer.*.'),
            $this->getCodeableConceptDataRules('location.*.'),
            $this->getReferenceDataRules('location.*.'),
            $this->getCodeableConceptDataRules('reason.*.'),
            $this->getReferenceDataRules('reason.*.'),
            $this->getReferenceDataRules('insurance.*.'),
            $this->getReferenceDataRules('supporting_info.*.'),
            $this->getReferenceDataRules('specimen.*.'),
            $this->getCodeableConceptDataRules('body_site.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->getReferenceDataRules('relevant_history.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'service_request' => 'required|array',
            'identifier' => 'nullable|array',
            'based_on' => 'nullable|array',
            'replaces' => 'nullable|array',
            'category' => 'nullable|array',
            'order_detail' => 'nullable|array',
            'performer' => 'nullable|array',
            'location' => 'nullable|array',
            'reason' => 'nullable|array',
            'insurance' => 'nullable|array',
            'supporting_info' => 'nullable|array',
            'specimen' => 'nullable|array',
            'body_site' => 'nullable|array',
            'note' => 'nullable|array',
            'relevant_history' => 'nullable|array'
        ];
    }

    private function baseDataRules(string $prefix = null): array
    {
        return array_merge(
            $this->getIdentifierDataRules($prefix . 'requisition_'),
            [
                $prefix . 'status' => ['required', Rule::in(ServiceRequest::STATUS_CODE)],
                $prefix . 'intent' => ['required', Rule::in(ServiceRequest::INTENT_CODE)],
                $prefix . 'priority' => ['nullable', Rule::in(ServiceRequest::PRIORITY_CODE)],
                $prefix . 'do_not_perform' => 'nullable|boolean',
                $prefix . 'quantity' => 'nullable|array',
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'required|string',
                $prefix . 'occurrence' => 'nullable|array',
                $prefix . 'occurrence.occurrenceDateTime' => 'nullable|date',
                $prefix . 'as_needed' => 'nullable|array',
                $prefix . 'as_needed.asNeededBoolean' => 'nullable|boolean',
                $prefix . 'authored_on' => 'nullable|date',
                $prefix . 'requester' => 'nullable|string',
                $prefix . 'patient_instruction' => 'nullable|string',
            ],
            $this->getCodeableConceptDataRules($prefix . 'code_'),
            $this->getQuantityDataRules($prefix . 'quantity.quantityQuantity.', false),
            $this->getRatioDataRules($prefix . 'quantity.quantityRatio.', true),
            $this->getRangeDataRules($prefix . 'quantity.quantityRange.'),
            $this->getPeriodDataRules($prefix . 'occurrence.occurrencePeriod.'),
            $this->getTimingDataRules($prefix . 'occurrence.occurrenceTiming.'),
            $this->getCodeableConceptDataRules($prefix . 'as_needed.asNeededCodeableConcept.'),
            $this->getCodeableConceptDataRules($prefix . 'performer_type_'),
        );
    }
}
