<?php

namespace App\Http\Requests;

use App\Models\ClinicalImpression;
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
            $this->getReferenceDataRules('problem.*.'),
            $this->investigationDataRules('investigation.*.'),
            ['protocol.*.uri' => 'required|string'],
            $this->getCodeableConceptDataRules('finding.*.'),
            [
                'finding.*.reference' => 'nullable|string',
                'finding.*.basis' => 'nullable|string'
            ],
            $this->getCodeableConceptDataRules('prognosis.*.'),
            $this->getReferenceDataRules('prognosis.*.'),
            $this->getReferenceDataRules('supportingInfo.*.'),
            $this->getAnnotationDataRules('note.*.'),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'clinicalImpression' => 'required|array',
            'identifier' => 'nullable|array',
            'problem' => 'nullable|array',
            'investigation' => 'nullable|array',
            'protocol' => 'nullable|array',
            'finding' => 'nullable|array',
            'prognosis' => 'required|array',
            'supportingInfo' => 'nullable|array',
            'note' => 'nullable|array'
        ];
    }

    private function baseDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'status' => ['required', Rule::in(ClinicalImpression::STATUS_CODE)],
            $prefix . 'status_reason_system' => 'nullable|string',
            $prefix . 'status_reason_code' => 'nullable|string',
            $prefix . 'status_reason_display' => 'nullable|string',
            $prefix . 'status_reason_text' => 'nullable|string',
            $prefix . 'code_system' => 'nullable|string',
            $prefix . 'code_code' => 'nullable|string',
            $prefix . 'code_display' => 'nullable|string',
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
            $prefix . 'summary' => 'nullable|string',
        ];
    }

    private function investigationDataRules(string $prefix = null): array
    {
        return array_merge(
            [
                $prefix . 'investigation_data' => 'required|array',
                $prefix . 'investigation_data.system' => 'nullable|string',
                $prefix . 'investigation_data.code' => ['nullable', Rule::in(ClinicalImpressionInvestigation::CODE)],
                $prefix . 'investigation_data.display' => 'nullable|string',
                $prefix . 'investigation_data.text' => 'nullable|string',
                $prefix . 'item' => 'nullable|array'
            ],
            $this->getReferenceDataRules($prefix . 'item.*.')
        );
    }
}
