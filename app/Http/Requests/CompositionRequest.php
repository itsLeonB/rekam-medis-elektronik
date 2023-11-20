<?php

namespace App\Http\Requests;

use App\Models\Composition;
use App\Models\CompositionAttester;
use App\Models\CompositionRelatesTo;
use App\Models\CompositionSection;
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
            $this->getCodeableConceptDataRules('category.*.'),
            $this->getReferenceDataRules('author.*.'),
            $this->attesterDataRules('attester.*.'),
            $this->relatesToDataRules('relatesTo.*.'),
            $this->eventDataRules('event.*.'),
            $this->sectionDataRules('section.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'composition' => 'required|array',
            'category' => 'nullable|array',
            'author' => 'required|array',
            'attester' => 'nullable|array',
            'relatesTo' => 'nullable|array',
            'event' => 'nullable|array',
            'section' => 'nullable|array'
        ];
    }

    private function baseDataRules(string $prefix = null): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules($prefix . 'type_'),
            [
                $prefix . 'status' => ['required', Rule::in(Composition::STATUS_CODE)],
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'nullable|string',
                $prefix . 'date' => 'required|date',
                $prefix . 'title' => 'required|string',
                $prefix . 'confidentiality' => ['nullable', Rule::in(Composition::CONFIDENTIALITY_CODE)],
                $prefix . 'custodian' => 'nullable|string'
            ]
        );
    }

    private function attesterDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'mode' => ['required', Rule::in(CompositionAttester::MODE_CODE)],
            $prefix . 'time' => 'nullable|date',
            $prefix . 'party' => 'nullable|string'
        ];
    }

    private function relatesToDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'code' => ['required', Rule::in(CompositionRelatesTo::CODE_CODE)],
                $prefix . 'target' => 'required|array',
                $prefix . 'target.targetIdentifier' => 'nullable|array',
                $prefix . 'target.targetReference' => 'nullable|string'
            ],
            $this->getIdentifierDataRules($prefix . 'target.targetIdentifier.')
        );
    }

    private function eventDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'event_data' => 'required|array',
                $prefix . 'code' => 'nullable|array',
                $prefix . 'detail' => 'nullable|array'
            ],
            $this->getPeriodDataRules($prefix . 'event_data.period_'),
            $this->getCodeableConceptDataRules($prefix . 'code.*.'),
            $this->getReferenceDataRules($prefix . 'detail.*.')
        );
    }

    private function sectionDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'section_data' => 'required|array',
                $prefix . 'author' => 'nullable|array',
                $prefix . 'entry' => 'nullable|array',
                $prefix . 'section_data.title' => 'nullable|string',
                $prefix . 'section_data.code' => ['nullable', Rule::in(CompositionSection::CODE_CODE)],
                $prefix . 'section_data.focus' => 'nullable|string',
                $prefix . 'section_data.mode' => ['nullable', Rule::in(CompositionSection::MODE_CODE)],
                $prefix . 'section_data.ordered_by' => ['nullable', Rule::in(CompositionSection::ORDERED_BY_CODE)],
                $prefix . 'section_data.empty_reason' => ['nullable', Rule::in(CompositionSection::EMPTY_REASON_CODE)]
            ],
            $this->getNarrativeDataRules($prefix . 'section_data.text_', true),
            $this->getReferenceDataRules($prefix . 'author.*.'),
            $this->getReferenceDataRules($prefix . 'entry.*.')
        );
    }
}
