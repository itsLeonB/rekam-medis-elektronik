<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\{
    AllergyIntolerance,
    AllergyIntoleranceReaction
};
use Illuminate\Validation\Rule;

class AllergyIntoleranceRequest extends FhirRequest
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
            $this->baseDataRules('allergyIntolerance.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->reactionDataRules('reaction.*.')
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'allergyIntolerance' => 'required|array',
            'identifier' => 'nullable|array',
            'note' => 'nullable|array',
            'reaction' => 'nullable|array',
        ];
    }

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'clinical_status' => ['nullable', Rule::in(AllergyIntolerance::CLINICAL_STATUS['binding']['valueset']['code'])],
                $prefix . 'verification_status' => ['nullable', Rule::in(AllergyIntolerance::VERIFICATION_STATUS['binding']['valueset']['code'])],
                $prefix . 'type' => ['nullable', Rule::in(AllergyIntolerance::TYPE['binding']['valueset']['code'])],
                $prefix . 'category' => 'required|array',
                $prefix . 'category.*' => ['required', Rule::in(AllergyIntolerance::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'criticality' => ['nullable', Rule::in(AllergyIntolerance::CRITICALITY['binding']['valueset']['code'])],
                $prefix . 'code_system' => 'required|string',
                $prefix . 'code_code' => 'required|string',
                $prefix . 'code_display' => 'required|string',
                $prefix . 'patient' => 'required|string',
                $prefix . 'encounter' => 'nullable|string',
                $prefix . 'recorded_date' => 'nullable|date',
                $prefix . 'recorder' => 'nullable|string',
                $prefix . 'asserter' => 'nullable|string',
                $prefix . 'last_occurence' => 'nullable|date',
            ],
            $this->getOnsetAttributeDataRules($prefix . ''),
        );
    }

    private function reactionDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'reaction_data' => 'required|array',
                $prefix . 'reaction_data.substance_system' => 'nullable|string',
                $prefix . 'reaction_data.substance_code' => 'nullable|string',
                $prefix . 'reaction_data.substance_display' => 'nullable|string',
                $prefix . 'reaction_data.manifestation' => 'required|array',
                $prefix . 'reaction_data.manifestation.*' => ['nullable', Rule::in(AllergyIntoleranceReaction::MANIFESTATION['binding']['valueset']['code'])],
                $prefix . 'reaction_data.description' => 'nullable|string',
                $prefix . 'reaction_data.onset' => 'nullable|date',
                $prefix . 'reaction_data.severity' => ['nullable', Rule::in(AllergyIntoleranceReaction::SEVERITY['binding']['valueset']['code'])],
                $prefix . 'reaction_data.exposure_route' => ['nullable', Rule::in(AllergyIntoleranceReaction::EXPOSURE_ROUTE['binding']['valueset']['code'])],
            ],
            $this->getAnnotationDataRules($prefix . 'note.*.'),
        );
    }

    // public function messages(): array
    // {
    //     // create the corresponding validation error message according to the rules above
    //     return [
    //         //Untuk required
    //         $prefix . 'category_food.required' => 'Keterangan intoleransi/alergi makanan harus diisi',
    //         $prefix . 'category_medication.required' => 'Keterangan intoleransi/alergi medikasi harus diisi',
    //         $prefix . 'category_environment.required' => 'Keterangan intoleransi/alergi lingkungan harus diisi',
    //         $prefix . 'category_biologic.required' => 'Keterangan intoleransi/alergi biologis harus diisi',
    //         $prefix . 'code_code.required' => 'Kode intoleransi/alergi harus diisi',
    //         $prefix . 'patient.required' => 'Data pasien harus diisi',

    //         'identifier.*.system.required' => 'Sistem identifier harus diisi',
    //         'identifier.*.use.required' => 'Identifier use harus diisi',
    //         'identifier.*.value.required' => 'Identifier value harus diisi',

    //         $prefix . 'reaction_data.required' => 'Data reaksi intoleransi/alergi harus diisi',
    //         $prefix . 'manifestation.required' => 'Data manifestasi reaksi intoleransi/alergi harus diisi',
    //         $prefix . 'manifestation.*.code.required' => 'Kode manifestasi reaksi intoleransi/alergi harus diisi',
    //         $prefix . 'note.*.text.required' => 'Teks untuk catatan reaksi intoleransi/alergi harus diisi',

    //         //Untuk rule::in
    //         $prefix . 'clinical_status.in' => 'Harus termasuk "active", "inactive", atau "resolved"',
    //         $prefix . 'verification_status.in' => 'Harus termasuk "unconfirmed", "confirmed", "refuted", atau "entered-in-error"',
    //         $prefix . 'type.in' => 'Harus termasuk "allergy" atau "intolerance"',
    //         $prefix . 'criticality.in' => 'Harus termasuk "low", "high", atau "unable-to-assess"',
    //         $prefix . 'onset.onsetAge.comparator.in' => 'Harus termasuk "<", "<=", ">=", atau ">"',

    //         'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

    //         $prefix . 'reaction_data.severity.in' => 'Harus termasuk "mild", "moderate", atau "severe"',
    //         $prefix . 'manifestation.*.code.in' => 'Harus termasuk "1985008", "4386001", "9826008", "23924001", "24079001", "31996006", "39579001", "41291007", "43116000", "49727002", "51599000", "62315008", "70076002", "73442001", "76067001", "91175000", "126485001", "162290004", "195967001", "247472004", "267036007", "271757001", "271759003", "271807003", "410430005", "418363000", "422587007", "698247007", "702809001", "768962006"',

    //         //Untuk decimal
    //         $prefix . 'onset.onsetAge.value.decimal' => 'Onset age harus berbentuk desimal',
    //         $prefix . 'onset.onsetRange.low.value.decimal' => 'Low value onset range harus berbentuk desimal',
    //         $prefix . 'onset.onsetRange.high.value.decimal' => 'High value onset range harus berbentuk desimal'
    //     ];
    // }
}
