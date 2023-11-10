<?php

namespace App\Http\Requests;

use App\Models\Observation;
use App\Models\ObservationCategory;
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
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getReferenceDataRules('based_on.*.'),
            $this->getReferenceDataRules('part_of.*.'),
            $this->getCodeableConceptDataRules('category.*.', ObservationCategory::CODE),
            $this->getReferenceDataRules('focus.*.'),
            $this->getReferenceDataRules('performer.*.'),
            $this->getCodeableConceptDataRules('interpretation.*.', ObservationInterpretation::CODE),
            $this->getAnnotationDataRules('note.*.'),
            $this->getReferenceRangeDataRules('reference_range.*.'),
            $this->getReferenceDataRules('has_member.*.'),
            $this->getReferenceDataRules('derived_from.*.'),
            $this->componentDataRules(),
            $this->getReferenceRangeDataRules('component.*.reference_range.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'observation' => 'required|array',
            'identifier' => 'nullable|array',
            'based_on' => 'nullable|array',
            'part_of' => 'nullable|array',
            'category' => 'nullable|array',
            'focus' => 'nullable|array',
            'performer' => 'nullable|array',
            'interpretation' => 'nullable|array',
            'note' => 'nullable|array',
            'reference_range' => 'nullable|array',
            'has_member' => 'nullable|array',
            'derived_from' => 'nullable|array',
            'component' => 'nullable|array',
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'observation.status' => ['required', Rule::in(Observation::STATUS_CODE)],
                'observation.subject' => 'required|string',
                'observation.encounter' => 'required|string',
                'observation.issued' => 'nullable|date',
                'observation.data_absent_reason' => ['nullable', Rule::in(Observation::DATA_ABSENT_REASON_CODE)],
                'observation.body_site_system' => 'nullable|string',
                'observation.body_site_code' => 'nullable|string',
                'observation.body_site_display' => 'nullable|string',
                'observation.method_system' => 'nullable|string',
                'observation.method_code' => 'nullable|string',
                'observation.method_display' => 'nullable|string',
                'observation.specimen' => 'nullable|string',
                'observation.device' => 'nullable|string',
            ],
            $this->getCodeableConceptDataRules('observation.code_'),
            $this->getEffectiveDataRules('observation.'),
            $this->getValueDataRules('observation.'),
        );
    }

    private function getReferenceRangeDataRules($prefix = null): array
    {
        return [
            $prefix . 'value_low' => 'nullable|decimal',
            $prefix . 'value_high' => 'nullable|decimal',
            $prefix . 'unit' => 'nullable|string',
            $prefix . 'system' => 'nullable|string',
            $prefix . 'code' => 'nullable|string',
            $prefix . 'type' => ['nullable', Rule::in(ObservationReferenceRange::CODE)],
            $prefix . 'applies_to' => 'nullable|array',
            $prefix . 'applies_to.*.coding' => 'nullable|array',
            $prefix . 'applies_to.*.coding.*.system' => 'nullable|string',
            $prefix . 'applies_to.*.coding.*.code' => 'nullable|string',
            $prefix . 'applies_to.*.coding.*.display' => 'nullable|string',
            $prefix . 'applies_to.*.text' => 'nullable|string',
            $prefix . 'age_low' => 'nullable|integer|gte:0',
            $prefix . 'age_high' => 'nullable|integer|gte:0',
            $prefix . 'text' => 'nullable|string',
        ];
    }

    private function componentDataRules(): array
    {
        return array_merge(
            [
                'component.*.component_data' => 'required|array',
                'component.*.component_data.data_absent_reason' => ['nullable', Rule::in(Observation::DATA_ABSENT_REASON_CODE)],
                'component.*.interpretation' => 'nullable|array',
            ],
            $this->getCodeableConceptDataRules('component.*.component_data.code_'),
            $this->getValueDataRules('component.*.component_data.'),
            $this->getCodeableConceptDataRules('component.*.interpretation.*.', ObservationInterpretation::CODE),
        );
    }
}
