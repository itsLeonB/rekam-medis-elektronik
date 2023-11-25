<?php

namespace App\Http\Requests;

use App\Models\ImagingStudy;
use App\Models\ImagingStudySeries;
use App\Models\ImagingStudySeriesInstance;
use Illuminate\Validation\Rule;

class ImagingStudyRequest extends FhirRequest
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
            $this->baseDataRules('imagingStudy.'),
            $this->getCodeableConceptDataRules('reasonCode.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->seriesDataRules('series.*.')
        );
    }


    private function baseAttributeRules(): array
    {
        return [
            'imagingStudy' => 'required|array',
            'reasonCode' => 'nullable|array',
            'note' => 'nullable|array',
            'series' => 'nullable|array'
        ];
    }


    private function baseDataRules($prefix): array
    {
        return [
            $prefix . 'status' => ['required', Rule::in(ImagingStudy::STATUS_CODE)],
            $prefix . 'modality' => 'required|array',
            $prefix . 'modality.*' => ['required', Rule::in(ImagingStudy::MODALITY_CODE)],
            $prefix . 'subject' => 'required|string',
            $prefix . 'encounter' => 'nullable|string',
            $prefix . 'started' => 'nullable|date',
            $prefix . 'based_on' => 'required|array',
            $prefix . 'based_on.*' => 'required|string',
            $prefix . 'referrer' => 'nullable|string',
            $prefix . 'interpreter' => 'nullable|array',
            $prefix . 'interpreter.*' => 'required|string',
            $prefix . 'endpoint' => 'nullable|array',
            $prefix . 'endpoint.*' => 'required|string',
            $prefix . 'series_num' => 'nullable|integer|gte:0',
            $prefix . 'instances_num' => 'nullable|integer|gte:0',
            $prefix . 'procedure_reference' => 'nullable|string',
            $prefix . 'procedure_code' => 'nullable|array',
            $prefix . 'procedure_code.*' => 'required|string|exists:codesystem_loinc,code',
            $prefix . 'location' => 'nullable|string',
            $prefix . 'reason_reference' => 'nullable|array',
            $prefix . 'reason_reference.*' => 'required|string',
            $prefix . 'description' => 'nullable|string'
        ];
    }


    private function seriesDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'series_data.uid' => 'required|string',
                $prefix . 'series_data.number' => 'nullable|integer|gte:0',
                $prefix . 'series_data.modality' => ['required', Rule::in(ImagingStudySeries::MODALITY_CODE)],
                $prefix . 'series_data.description' => 'nullable|string',
                $prefix . 'series_data.num_instances' => 'nullable|integer|gte:0',
                $prefix . 'series_data.endpoint' => 'nullable|array',
                $prefix . 'series_data.endpoint.*' => 'required|string',
                $prefix . 'series_data.laterality' => ['nullable', Rule::in(ImagingStudySeries::LATERALITY_CODE)],
                $prefix . 'series_data.specimen' => 'nullable|array',
                $prefix . 'series_data.specimen.*' => 'required|string',
                $prefix . 'series_data.started' => 'nullable|date',
                $prefix . 'series_data.performer' => 'nullable|array',
                $prefix . 'series_data.performer.*.function' => ['nullable', Rule::in(ImagingStudySeries::PERFORMER_FUNCTION_CODE)],
                $prefix . 'series_data.performer.*.actor' => 'required|string',
            ],
            $this->getCodeableConceptDataRules($prefix . 'series_data.body_site_'),
            $this->instanceDataRules($prefix . 'instance.*.')
        );
    }


    private function instanceDataRules($prefix): array
    {
        return [
            $prefix . 'uid' => 'required|string',
            $prefix . 'sop_class' => ['required', Rule::in(ImagingStudySeriesInstance::SOPCLASS_CODE)],
            $prefix . 'number' => 'nullable|integer|gte:0',
            $prefix . 'title' => 'nullable|string'
        ];
    }
}
