<?php

namespace App\Http\Requests;

use App\Models\Observation;
use App\Models\ObservationCategory;
use App\Models\ObservationComponent;
use App\Models\ObservationInterpretation;
use App\Models\ObservationReferenceRange;
use Illuminate\Validation\Rule;

class ObservationRequest extends FhirRequest
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
            $this->baseDataRules('observation.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->referenceRangeDataRules('referenceRange.*.'),
            $this->componentDataRules('component.*.'),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'observation' => 'required|array',
            'identifier' => 'nullable|array',
            'note' => 'nullable|array',
            'referenceRange' => 'nullable|array',
            'component' => 'nullable|array',
        ];
    }

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'based_on' => 'nullable|array',
                $prefix . 'based_on.*' => 'required|string',
                $prefix . 'part_of' => 'nullable|array',
                $prefix . 'part_of.*' => 'required|string',
                $prefix . 'status' => ['required', Rule::in(Observation::STATUS['binding']['valueset']['code'])],
                $prefix . 'category' => 'nullable|array',
                $prefix . 'category.*' => ['required', Rule::in(Observation::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'code' => ['required', Rule::exists(Observation::CODE['binding']['valueset']['table'], 'code')],
                $prefix . 'subject' => 'required|string',
                $prefix . 'focus' => 'nullable|array',
                $prefix . 'focus.*' => 'required|string',
                $prefix . 'encounter' => 'required|string',
                $prefix . 'issued' => 'nullable|date',
                $prefix . 'performer' => 'nullable|array',
                $prefix . 'performer.*' => 'required|string',
                $prefix . 'data_absent_reason' => ['nullable', Rule::in(Observation::DATA_ABSENT_REASON['binding']['valueset']['code'])],
                $prefix . 'interpretation' => 'nullable|array',
                $prefix . 'interpretation.*' => 'required|string',
                $prefix . 'body_site' => ['nullable', Rule::exists(Observation::BODY_SITE['binding']['valueset']['table'], 'code')],
                $prefix . 'method' => 'nullable|integer|gte:0',
                $prefix . 'specimen' => 'nullable|string',
                $prefix . 'device' => 'nullable|string',
                $prefix . 'has_member' => 'nullable|array',
                $prefix . 'has_member.*' => 'required|string',
                $prefix . 'derived_from' => 'nullable|array',
                $prefix . 'derived_from.*' => 'required|string',
            ],
            $this->getCodeableConceptDataRules($prefix . 'code_'),
            $this->getEffectiveDataRules($prefix),
            $this->getValueDataRules($prefix),
        );
    }

    private function referenceRangeDataRules($prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'type' => ['nullable', Rule::in(ObservationReferenceRange::TYPE['binding']['valueset']['code'])],
                $prefix . 'applies_to' => 'nullable|array',
                $prefix . 'applies_to.*' => ['required', Rule::exists(ObservationReferenceRange::APPLIES_TO['binding']['valueset']['table'], 'code')],
                $prefix . 'age_low' => 'nullable|integer|gte:0',
                $prefix . 'age_high' => 'nullable|integer|gte:0',
                $prefix . 'text' => 'nullable|string',
            ],
            $this->getQuantityDataRules($prefix . 'low_', true),
            $this->getQuantityDataRules($prefix . 'high_', true),
        );
    }

    private function componentDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'component_data' => 'required|array',
                $prefix . 'component_data.code' => ['required', Rule::exists(ObservationComponent::CODE['binding']['valueset']['table'], 'code')],
                $prefix . 'component_data.data_absent_reason' => ['nullable', Rule::in(ObservationComponent::DATA_ABSENT_REASON['binding']['valueset']['code'])],
                $prefix . 'interpretation' => 'nullable|array',
                $prefix . 'interpretation.*' => ['required', Rule::in(ObservationComponent::INTERPRETATION['binding']['valueset']['code'])],
            ],
            $this->getValueDataRules($prefix . 'component_data.'),
            $this->referenceRangeDataRules($prefix . 'referenceRange.*.'),
        );
    }
}
