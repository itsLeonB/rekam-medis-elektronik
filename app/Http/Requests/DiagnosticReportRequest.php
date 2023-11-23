<?php

namespace App\Http\Requests;

use App\Models\DiagnosticReport;
use Illuminate\Validation\Rule;

class DiagnosticReportRequest extends FhirRequest
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
            $this->baseDataRules('diagnostic.'),
            $this->mediaDataRules('media.*.'),
            $this->getCodeableConceptDataRules('conclusionCode.*.')
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'diagnostic' => 'required|array',
            'media' => 'nullable|array',
            'conclusionCode' => 'nullable|array'
        ];
    }


    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'status' => ['required', Rule::in(DiagnosticReport::STATUS_CODE)],
                $prefix . 'code' => 'required|string|exists:code_system_loinc,code',
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'required|string',
                $prefix . 'issued' => 'nullable|date',
                $prefix . 'conclusion' => 'nullable|string',
            ],
            $this->multiDataRules($prefix . 'based_on'),
            $this->multiDataRules($prefix . 'category'),
            $this->effectiveDataRules($prefix . 'effective'),
            $this->multiDataRules($prefix . 'performer'),
            $this->multiDataRules($prefix . 'results_interpreter'),
            $this->multiDataRules($prefix . 'specimen'),
            $this->multiDataRules($prefix . 'result'),
            $this->multiDataRules($prefix . 'imaging_study'),
        );
    }


    private function effectiveDataRules($prefix): array
    {
        return [
            $prefix => 'nullable|array',
            $prefix . 'effectiveDateTime' => 'nullable|date',
            $prefix . 'effectivePeriod' => 'nullable|array',
            $prefix . 'effectivePeriod.start' => 'nullable|date',
            $prefix . 'effectivePeriod.end' => 'nullable|date',
        ];
    }


    private function mediaDataRules($prefix): array
    {
        return [
            $prefix . 'comment' => 'nullable|string',
            $prefix . 'link' => 'required|string',
        ];
    }


    private function multiDataRules($prefix): array
    {
        return [
            $prefix => 'nullable|array',
            $prefix . '.*' => 'nullable|string',
        ];
    }
}
