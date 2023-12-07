<?php

namespace App\Http\Requests;

use App\Models\ClinicalImpression;
use App\Models\ClinicalImpressionFinding;
use App\Models\ClinicalImpressionInvestigation;
use Illuminate\Validation\Rule;

class ClinicalImpressionRequest extends FhirRequest
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
            $this->baseDataRules('clinicalImpression.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->investigationDataRules('investigation.*.'),
            $this->findingDataRules('finding.*.'),
            $this->getAnnotationDataRules('note.*.'),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'clinicalImpression' => 'required|array',
            'identifier' => 'nullable|array',
            'investigation' => 'nullable|array',
            'finding' => 'nullable|array',
            'note' => 'nullable|array'
        ];
    }

    private function baseDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'status' => ['required', Rule::in(ClinicalImpression::STATUS['binding']['valueset']['code'])],
            $prefix . 'status_reason_code' => ['nullable', Rule::exists(ClinicalImpression::STATUS_REASON_CODE['binding']['valueset']['table'], 'code')],
            $prefix . 'status_reason_text' => 'nullable|string',
            $prefix . 'code_system' => 'nullable|string',
            $prefix . 'code_code' => 'nullable|string',
            $prefix . 'code_display' => 'nullable|string',
            $prefix . 'code_text' => 'nullable|string',
            $prefix . 'description' => 'nullable|string',
            $prefix . 'subject' => 'required|string',
            $prefix . 'encounter' => 'required|string',
            $prefix . 'effective' => 'nullable|array',
            $prefix . 'effective.effectiveDateTime' => 'nullable|date',
            $prefix . 'effective.effectivePeriod' => 'nullable|array',
            $prefix . 'effective.effectivePeriod.start' => 'nullable|date',
            $prefix . 'effective.effectivePeriod.end' => 'nullable|date',
            $prefix . 'date' => 'nullable|date',
            $prefix . 'assessor' => 'nullable|string',
            $prefix . 'previous' => 'nullable|string',
            $prefix . 'problem' => 'nullable|array',
            $prefix . 'problem.*' => 'nullable|string',
            $prefix . 'protocol' => 'nullable|array',
            $prefix . 'protocol.*' => 'nullable|string',
            $prefix . 'summary' => 'nullable|string',
            $prefix . 'prognosis_codeable_concept' => 'nullable|array',
            $prefix . 'prognosis_codeable_concept.*' => ['required', Rule::in(ClinicalImpression::PROGNOSIS_CODEABLE_CONCEPT['binding']['valueset']['code'])],
            $prefix . 'prognosis_reference' => 'nullable|array',
            $prefix . 'prognosis_reference.*' => 'required|string',
            $prefix . 'supporting_info' => 'nullable|array',
            $prefix . 'supporting_info.*' => 'required|string'
        ];
    }

    private function investigationDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'code' => ['nullable', Rule::in(ClinicalImpressionInvestigation::CODE['binding']['valueset']['code'])],
            $prefix . 'code_text' => 'nullable|string',
            $prefix . 'item' => 'nullable|array',
            $prefix . 'item.*' => 'required|string'
        ];
    }


    private function findingDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'item_codeable_concept' => ['nullable', Rule::exists(ClinicalImpressionFinding::ITEM_CODEABLE_CONCEPT['binding']['valueset']['table'], 'code')],
            $prefix . 'item_reference' => 'nullable|string',
            $prefix . 'basis' => 'nullable|string'
        ];
    }
}
