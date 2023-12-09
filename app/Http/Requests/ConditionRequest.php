<?php

namespace App\Http\Requests;

use App\Models\Condition;
use App\Models\ConditionCategory;
use App\Models\ConditionEvidence;
use App\Models\ConditionStage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ConditionRequest extends FhirRequest
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
            $this->baseDataRules('condition.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->stageDataRules('stage.*.'),
            $this->evidenceDataRules('evidence.*.'),
            $this->getAnnotationDataRules('note.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'condition' => 'required|array',
            'identifier' => 'nullable|array',
            'stage' => 'nullable|array',
            'evidence' => 'nullable|array',
            'note' => 'nullable|array',
        ];
    }

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'clinical_status' => ['nullable', Rule::in(Condition::CLINICAL_STATUS['binding']['valueset']['code'])],
                $prefix . 'verification_status' => ['nullable', Rule::in(Condition::VERIFICATION_STATUS['binding']['valueset']['code'])],
                $prefix . 'category' => 'nullable|array',
                $prefix . 'category.*' => ['required', Rule::in(Condition::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'severity' => ['nullable', Rule::in(Condition::SEVERITY['binding']['valueset']['code'])],
                $prefix . 'code_system' => 'required|string',
                $prefix . 'code_code' => 'required|string',
                $prefix . 'code_display' => 'required|string',
                $prefix . 'body_site' => 'nullable|array',
                $prefix . 'body_site.*' => ['required', Rule::exists(Condition::BODY_SITE['binding']['valueset']['table'], 'code')],
                $prefix . 'subject' => 'required|string',
                $prefix . 'encounter' => 'required|string',
                $prefix . 'recorded_date' => 'nullable|date',
                $prefix . 'recorder' => 'nullable|string',
                $prefix . 'asserter' => 'nullable|string',
            ],
            $this->getOnsetAttributeDataRules($prefix),
            $this->getAbatementAttributeDataRules($prefix)
        );
    }

    private function stageDataRules($prefix): array
    {
        return [
            $prefix . 'summary' => ['nullable', Rule::in(ConditionStage::SUMMARY['binding']['valueset']['code'])],
            $prefix . 'assessment' => 'nullable|array',
            $prefix . 'assessment.*' => 'required|string',
            $prefix . 'type' => ['nullable', Rule::exists(ConditionStage::TYPE['binding']['valueset']['table'], 'code')],
        ];
    }

    private function evidenceDataRules($prefix): array
    {
        return [
            $prefix . 'code' => 'nullable|array',
            $prefix . 'code.*' => 'required|integer|gte:0',
            $prefix . 'detail' => 'nullable|array',
            $prefix . 'detail.*' => 'required|string',
        ];
    }

    // TODO
    // public function messages(): array
    // {
    //     // create the corresponding validation error message according to the rules above
    //     return [
    //         //Untuk required
    //         $prefix . 'code.required' => 'Kode harus diisi',
    //         $prefix . 'subject.required' => 'Subjek harus diisi',
    //         $prefix . 'encounter.required' => 'Encounter harus diisi',

    //         'identifier.*.system.required' => 'System identifier harus diisi',
    //         'identifier.*.use.required' => 'Identifier use harus diisi',
    //         'identifier.*.value.required' => 'Identifier value harus diisi',

    //         'category.*.system.required' => 'Sistem data kategori harus diisi',
    //         'category.*.code.required' => 'Kode data kategori harus diisi',
    //         'category.*.display.required' => 'Display data kategori harus diisi',

    //         'bodySite.*.system.required' => 'Sistem data body site harus diisi',
    //         'bodySite.*.code.required' => 'Kode data body site harus diisi',
    //         'bodySite.*.display.required' => 'Display untuk data body site harus diisi',

    //         $prefix . 'required' => 'Data tahapan kondisi harus diisi',
    //         $prefix . 'assessment.*.reference.required' => 'Referensi data uji kondisi harus diisi',

    //         //Untuk Rule::in
    //         $prefix . 'clinical_status.in' => 'Harus termasuk "active", "recurrence", "relapse", "inactive", "remission", atau "resolved"',
    //         $prefix . 'verification_status.in' => 'Harus termasuk "unconfirmed", "provisional", "differential", "confirmed", "refuted", atau "entered-in-error"',
    //         $prefix . 'severity.in' => 'Harus termasuk "24484000", "6736007", atau "255604002"',

    //         $prefix . 'onset.onsetAge.comparator.in' => 'Harus termasuk "<", "<=", ">=", atau ">"',
    //         $prefix . 'abatement.abatementAge.comparator.in' => 'Harus termasuk "<", "<=", ">=", atau ">"',

    //         'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

    //         'category.*.code.in' => 'Harus termasuk "problem-list-item" atau "encounter-diagnosis"',

    //         //Untuk exists
    //         $prefix . 'code.exists' => 'Harus merupakan kode ICD-10',

    //         //Untuk gte
    //         $prefix . 'code.gte' => 'Nilai kode evidence tidak boleh negatif',

    //         //Untuk decimal
    //         $prefix . 'onset.onsetAge.value.decimal' => 'Nilai onset age harus berbentuk desimal',
    //         $prefix . 'onset.onsetRange.low.value.decimal' => 'Nilai low value dalam onset range harus berbentuk desimal',
    //         $prefix . 'onset.onsetRange.high.value.decimal' => 'Nilai high value dalam onset range harus berbentuk desimal',
    //         $prefix . 'abatement.abatementAge.value.decimal' => 'Nilai abatement age harus berbentuk desimal',
    //         $prefix . 'abatement.abatementRange.low.value.decimal' => 'Nilai low value dalam abatement range harus berbentuk desimal',
    //         $prefix . 'abatement.abatementRange.high.value.decimal' => 'Nilai high value dalam abatement range harus berbentuk desimal'
    //     ];
    // }
}
