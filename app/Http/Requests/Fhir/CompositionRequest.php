<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\{
    Composition,
    CompositionAttester,
    CompositionEvent,
    CompositionRelatesTo,
    CompositionSection
};
use Illuminate\Validation\Rule;

class CompositionRequest extends FhirRequest
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
            $this->baseDataRules('composition.'),
            $this->attesterDataRules('attester.*.'),
            $this->relatesToDataRules('relatesTo.*.'),
            $this->eventDataRules('event.*.'),
            $this->sectionDataRules('section.*.'),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'composition' => 'required|array',
            'attester' => 'nullable|array',
            'relatesTo' => 'nullable|array',
            'event' => 'nullable|array',
            'section' => 'nullable|array'
        ];
    }

    private function baseDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'status' => ['required', Rule::in(Composition::STATUS['binding']['valueset']['code'])],
            $prefix . 'type_system' => 'required|string',
            $prefix . 'type_code' => 'required|string',
            $prefix . 'type_display' => 'required|string',
            $prefix . 'category' => 'nullable|array',
            $prefix . 'category.*' => ['required', Rule::in(Composition::CATEGORY['binding']['valueset']['code'])],
            $prefix . 'subject' => 'required|string',
            $prefix . 'encounter' => 'nullable|string',
            $prefix . 'date' => 'required|date',
            $prefix . 'author' => 'nullable|array',
            $prefix . 'author.*' => 'required|string',
            $prefix . 'title' => 'required|string',
            $prefix . 'confidentiality' => ['nullable', Rule::in(Composition::CONFIDENTIALITY['binding']['valueset']['code'])],
            $prefix . 'custodian' => 'nullable|string'
        ];
    }

    private function attesterDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'mode' => ['required', Rule::in(CompositionAttester::MODE['binding']['valueset']['code'])],
            $prefix . 'time' => 'nullable|date',
            $prefix . 'party' => 'nullable|string'
        ];
    }

    private function relatesToDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'code' => ['required', Rule::in(CompositionRelatesTo::CODE['binding']['valueset']['code'])],
                $prefix . 'target' => 'required|array',
                $prefix . 'target.targetIdentifier' => 'nullable|array',
                $prefix . 'target.targetReference' => 'nullable|array',
                $prefix . 'target.targetReference.reference' => 'required|string',
            ],
            $this->getIdentifierDataRules($prefix . 'target.targetIdentifier.')
        );
    }

    private function eventDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'code' => 'nullable|array',
            $prefix . 'code.*' => ['required', Rule::exists(CompositionEvent::CODE['binding']['valueset']['table'], 'code')],
            $prefix . 'period_start' => 'nullable|date',
            $prefix . 'period_end' => 'nullable|date',
            $prefix . 'detail' => 'nullable|array',
            $prefix . 'detail.*' => 'required|string'
        ];
    }

    private function sectionDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'title' => 'nullable|string',
                $prefix . 'code' => ['nullable', Rule::in(CompositionSection::CODE['binding']['valueset']['code'])],
                $prefix . 'author' => 'nullable|array',
                $prefix . 'author.*' => 'required|string',
                $prefix . 'focus' => 'nullable|string',
                $prefix . 'text_status' => ['nullable', Rule::in(CompositionSection::TEXT_STATUS['binding']['valueset']['code'])],
                $prefix . 'text_div' => 'nullable|string',
                $prefix . 'mode' => ['nullable', Rule::in(CompositionSection::MODE['binding']['valueset']['code'])],
                $prefix . 'ordered_by' => ['nullable', Rule::in(CompositionSection::ORDERED_BY['binding']['valueset']['code'])],
                $prefix . 'entry' => 'nullable|array',
                $prefix . 'entry.*' => 'required|string',
                $prefix . 'empty_reason' => ['nullable', Rule::in(CompositionSection::EMPTY_REASON['binding']['valueset']['code'])],
                $prefix . 'section' => 'nullable|array'
            ],
        );
    }
}
