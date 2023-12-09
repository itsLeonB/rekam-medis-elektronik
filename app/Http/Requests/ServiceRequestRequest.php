<?php

namespace App\Http\Requests;

use App\Fhir\Codesystems;
use App\Models\ServiceRequest;
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
            $this->baseDataRules('serviceRequest.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getAnnotationDataRules('note.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'serviceRequest' => 'required|array',
            'identifier' => 'nullable|array',
            'note' => 'nullable|array',
        ];
    }

    private function baseDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'based_on' => 'nullable|array',
                $prefix . 'based_on.*' => 'required|string',
                $prefix . 'replaces' => 'nullable|array',
                $prefix . 'replaces.*' => 'required|string',
                $prefix . 'requisition_system' => 'nullable|string',
                $prefix . 'requisition_use' => ['nullable', Rule::in(Codesystems::IdentifierUse['code'])],
                $prefix . 'requisition_value' => 'nullable|string',
                $prefix . 'status' => ['required', Rule::in(ServiceRequest::STATUS['binding']['valueset']['code'])],
                $prefix . 'intent' => ['required', Rule::in(ServiceRequest::INTENT['binding']['valueset']['code'])],
                $prefix . 'category' => 'nullable|array',
                $prefix . 'category.*' => ['required', Rule::in(ServiceRequest::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'priority' => ['nullable', Rule::in(ServiceRequest::PRIORITY['binding']['valueset']['code'])],
                $prefix . 'do_not_perform' => 'nullable|boolean',
                $prefix . 'code_system' => 'required|string',
                $prefix . 'code_code' => 'required|string',
                $prefix . 'code_display' => 'required|string',
                $prefix . 'order_detail' => 'nullable|array',
                $prefix . 'order_detail.*' => ['required', Rule::in(ServiceRequest::ORDER_DETAIL['binding']['valueset']['code'])],
                $prefix . 'quantity' => 'nullable|array',
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'required|string',
                $prefix . 'occurrence' => 'nullable|array',
                $prefix . 'occurrence.occurrenceDateTime' => 'nullable|date',
                $prefix . 'as_needed' => 'nullable|array',
                $prefix . 'as_needed.asNeededBoolean' => 'nullable|boolean',
                $prefix . 'as_needed.asNeededCodeableConcept' => 'nullable|array',
                $prefix . 'as_needed.asNeededCodeableConcept.system' => 'nullable|string',
                $prefix . 'as_needed.asNeededCodeableConcept.code' => ['sometimes', Rule::exists(ServiceRequest::AS_NEEDED['binding']['valueset']['table'], 'code')],
                $prefix . 'authored_on' => 'nullable|date',
                $prefix . 'requester' => 'nullable|string',
                $prefix . 'performer_type' => ['nullable', Rule::exists(ServiceRequest::PERFORMER_TYPE['binding']['valueset']['table'], 'code')],
                $prefix . 'performer' => 'nullable|array',
                $prefix . 'performer.*' => 'required|string',
                $prefix . 'location_code' => 'nullable|array',
                $prefix . 'location_code.*' => ['sometimes', Rule::in(ServiceRequest::LOCATION_CODE['binding']['valueset']['code'])],
                $prefix . 'location_reference' => 'nullable|array',
                $prefix . 'location_reference.*' => 'required|string',
                $prefix . 'reason_code' => 'nullable|array',
                $prefix . 'reason_code.*' => ['sometimes', Rule::exists(ServiceRequest::REASON_CODE['binding']['valueset']['table'], 'code')],
                $prefix . 'reason_reference' => 'nullable|array',
                $prefix . 'reason_reference.*' => 'required|string',
                $prefix . 'insurance' => 'nullable|array',
                $prefix . 'insurance.*' => 'required|string',
                $prefix . 'supporting_info' => 'nullable|array',
                $prefix . 'supporting_info.*' => 'required|string',
                $prefix . 'specimen' => 'nullable|array',
                $prefix . 'specimen.*' => 'required|string',
                $prefix . 'body_site' => 'nullable|array',
                $prefix . 'body_site.*' => ['sometimes', Rule::exists(ServiceRequest::BODY_SITE['binding']['valueset']['table'], 'code')],
                $prefix . 'patient_instruction' => 'nullable|string',
                $prefix . 'relevant_history' => 'nullable|array',
                $prefix . 'relevant_history.*' => 'required|string',
            ],
            $this->getQuantityDataRules($prefix . 'quantity.quantityQuantity.', false),
            $this->getRatioDataRules($prefix . 'quantity.quantityRatio.', true),
            $this->getRangeDataRules($prefix . 'quantity.quantityRange.'),
            $this->getPeriodDataRules($prefix . 'occurrence.occurrencePeriod.'),
            $this->getTimingDataRules($prefix . 'occurrence.occurrenceTiming.', true),
        );
    }
}
