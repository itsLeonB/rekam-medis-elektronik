<?php

namespace App\Http\Requests;

use App\Models\Condition;
use App\Models\ConditionCategory;
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
            $this->baseDataRules(),
            $this->getIdentifierDataRules(),
            $this->getCodeableConceptDataRules('category.', ConditionCategory::CATEGORY_CODE),
            $this->getCodeableConceptDataRules('body_site.'),
            $this->stageDataRules(),
            $this->evidenceDataRules(),
            $this->getAnnotationDataRules('note.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'condition' => 'required|array',
            'identifier' => 'nullable|array',
            'category' => 'nullable|array',
            'body_site' => 'nullable|array',
            'stage' => 'nullable|array',
            'evidence' => 'nullable|array',
            'note' => 'nullable|array',
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'condition.clinical_status' => ['nullable', Rule::in(Condition::CLINICAL_STATUS_CODE)],
                'condition.verification_status' => ['nullable', Rule::in(Condition::VERIFICATION_STATUS_CODE)],
                'condition.severity' => ['nullable', Rule::in(Condition::SEVERITY_CODE)],
                'condition.code' => 'required|string|exists:codesystem_icd10,code',
                'condition.subject' => 'required|string',
                'condition.encounter' => 'required|string',
                'condition.abatement' => 'nullable|array',
                'condition.recorded_date' => 'nullable|date',
                'condition.recorder' => 'nullable|string',
                'condition.asserter' => 'nullable|string',
            ],
            $this->getOnsetAttributeDataRules('condition.'),
            $this->getAbatementAttributeDataRules('condition.')
        );
    }

    private function stageDataRules(): array
    {
        return [
            'stage.*.stage_data' => 'required|array',
            'stage.*.stage_data.summary_system' => 'nullable|string',
            'stage.*.stage_data.summary_code' => 'nullable|string',
            'stage.*.stage_data.summary_display' => 'nullable|string',
            'stage.*.stage_data.type_system' => 'nullable|string',
            'stage.*.stage_data.type_code' => 'nullable|string',
            'stage.*.stage_data.type_display' => 'nullable|string',
            'stage.*.assessment' => 'nullable|array',
            'stage.*.assessment.*.reference' => 'required|string',
        ];
    }

    private function evidenceDataRules(): array
    {
        return [
            'evidence.*.system' => 'nullable|string',
            'evidence.*.code' => 'nullable|integer|gte:0',
            'evidence.*.display' => 'nullable|string',
            'evidence.*.detail_reference' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        // create the corresponding validation error message according to the rules above
        return [
            //Untuk required
            'condition.code.required' => 'Kode harus diisi',
            'condition.subject.required' => 'Subjek harus diisi',
            'condition.encounter.required' => 'Encounter harus diisi',

            'identifier.*.system.required' => 'System identifier harus diisi',
            'identifier.*.use.required' => 'Identifier use harus diisi',
            'identifier.*.value.required' => 'Identifier value harus diisi',

            'category.*.system.required' => 'Sistem data kategori harus diisi',
            'category.*.code.required' => 'Kode data kategori harus diisi',
            'category.*.display.required' => 'Display data kategori harus diisi',

            'body_site.*.system.required' => 'Sistem data body site harus diisi',
            'body_site.*.code.required' => 'Kode data body site harus diisi',
            'body_site.*.display.required' => 'Display untuk data body site harus diisi',

            'stage.*.stage_data.required' => 'Data tahapan kondisi harus diisi',
            'stage.*.assessment.*.reference.required' => 'Referensi data uji kondisi harus diisi',

            //Untuk Rule::in
            'condition.clinical_status.in' => 'Harus termasuk "active", "recurrence", "relapse", "inactive", "remission", atau "resolved"',
            'condition.verification_status.in' => 'Harus termasuk "unconfirmed", "provisional", "differential", "confirmed", "refuted", atau "entered-in-error"',
            'condition.severity.in' => 'Harus termasuk "24484000", "6736007", atau "255604002"',

            'condition.onset.onsetAge.comparator.in' => 'Harus termasuk "<", "<=", ">=", atau ">"',
            'condition.abatement.abatementAge.comparator.in' => 'Harus termasuk "<", "<=", ">=", atau ">"',

            'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

            'category.*.code.in' => 'Harus termasuk "problem-list-item" atau "encounter-diagnosis"',

            //Untuk exists
            'condition.code.exists' => 'Harus merupakan kode ICD-10',

            //Untuk gte
            'evidence.*.code.gte' => 'Nilai kode evidence tidak boleh negatif',

            //Untuk decimal
            'condition.onset.onsetAge.value.decimal' => 'Nilai onset age harus berbentuk desimal',
            'condition.onset.onsetRange.low.value.decimal' => 'Nilai low value dalam onset range harus berbentuk desimal',
            'condition.onset.onsetRange.high.value.decimal' => 'Nilai high value dalam onset range harus berbentuk desimal',
            'condition.abatement.abatementAge.value.decimal' => 'Nilai abatement age harus berbentuk desimal',
            'condition.abatement.abatementRange.low.value.decimal' => 'Nilai low value dalam abatement range harus berbentuk desimal',
            'condition.abatement.abatementRange.high.value.decimal' => 'Nilai high value dalam abatement range harus berbentuk desimal'
        ];
    }
}
