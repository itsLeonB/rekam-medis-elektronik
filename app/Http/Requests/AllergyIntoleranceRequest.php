<?php

namespace App\Http\Requests;

use App\Models\AllergyIntolerance;
use App\Models\AllergyIntoleranceReaction;
use App\Models\AllergyIntoleranceReactionManifestation;
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
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getAnnotationDataRules('note.*.'),
            $this->reactionDataRules()
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'allergy_intolerance' => 'required|array',
            'identifier' => 'nullable|array',
            'note' => 'nullable|array',
            'reaction' => 'nullable|array',
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'allergy_intolerance.clinical_status' => ['nullable', Rule::in(AllergyIntolerance::CLINICAL_STATUS_CODE)],
                'allergy_intolerance.verification_status' => ['nullable', Rule::in(AllergyIntolerance::VERIFICATION_STATUS_CODE)],
                'allergy_intolerance.type' => ['nullable', Rule::in(AllergyIntolerance::TYPE_CODE)],
                'allergy_intolerance.category_food' => 'required|boolean',
                'allergy_intolerance.category_medication' => 'required|boolean',
                'allergy_intolerance.category_environment' => 'required|boolean',
                'allergy_intolerance.category_biologic' => 'required|boolean',
                'allergy_intolerance.criticality' => ['nullable', Rule::in(AllergyIntolerance::CRITICALITY_CODE)],
                'allergy_intolerance.code_system' => 'nullable|string',
                'allergy_intolerance.code_code' => 'required|string',
                'allergy_intolerance.code_display' => 'nullable|string',
                'allergy_intolerance.patient' => 'required|string',
                'allergy_intolerance.encounter' => 'nullable|string',
                'allergy_intolerance.recorded_date' => 'nullable|date',
                'allergy_intolerance.recorder' => 'nullable|string',
                'allergy_intolerance.asserter' => 'nullable|string',
                'allergy_intolerance.last_occurence' => 'nullable|date',
            ],
            $this->getOnsetAttributeDataRules('allergy_intolerance.'),
        );
    }

    private function reactionDataRules(): array
    {
        return array_merge(
            [
                'reaction.*.reaction_data' => 'required|array',
                'reaction.*.reaction_data.substance_system' => 'nullable|string',
                'reaction.*.reaction_data.substance_code' => 'nullable|string',
                'reaction.*.reaction_data.substance_display' => 'nullable|string',
                'reaction.*.reaction_data.description' => 'nullable|string',
                'reaction.*.reaction_data.onset' => 'nullable|date',
                'reaction.*.reaction_data.severity' => ['nullable', Rule::in(AllergyIntoleranceReaction::SEVERITY_CODE)],
                'reaction.*.reaction_data.exposure_route_system' => 'nullable|string',
                'reaction.*.reaction_data.exposure_route_code' => 'nullable|string',
                'reaction.*.reaction_data.exposure_route_display' => 'nullable|string',
                'reaction.*.manifestation' => 'required|array',
                'reaction.*.manifestation.*.system' => 'nullable|string',
                'reaction.*.manifestation.*.code' => ['required', Rule::in(AllergyIntoleranceReactionManifestation::CODE)],
                'reaction.*.manifestation.*.display' => 'nullable|string',
            ],
            $this->getAnnotationDataRules('reaction.*.'),
        );
    }

    public function messages(): array
    {
        // create the corresponding validation error message according to the rules above
        return [
            //Untuk required
            'allergy_intolerance.category_food.required' => 'Keterangan intoleransi/alergi makanan harus diisi',
            'allergy_intolerance.category_medication.required' => 'Keterangan intoleransi/alergi medikasi harus diisi',
            'allergy_intolerance.category_environment.required' => 'Keterangan intoleransi/alergi lingkungan harus diisi',
            'allergy_intolerance.category_biologic.required' => 'Keterangan intoleransi/alergi biologis harus diisi',
            'allergy_intolerance.code_code.required' => 'Kode intoleransi/alergi harus diisi',
            'allergy_intolerance.patient.required' => 'Data pasien harus diisi',

            'identifier.*.system.required' => 'Sistem identifier harus diisi',
            'identifier.*.use.required' => 'Identifier use harus diisi',
            'identifier.*.value.required' => 'Identifier value harus diisi',

            'reaction.*.reaction_data.required' => 'Data reaksi intoleransi/alergi harus diisi',
            'reaction.*.manifestation.required' => 'Data manifestasi reaksi intoleransi/alergi harus diisi',
            'reaction.*.manifestation.*.code.required' => 'Kode manifestasi reaksi intoleransi/alergi harus diisi',
            'reaction.*.note.*.text.required' => 'Teks untuk catatan reaksi intoleransi/alergi harus diisi',

            //Untuk rule::in
            'allergy_intolerance.clinical_status.in' => 'Harus termasuk "active", "inactive", atau "resolved"',
            'allergy_intolerance.verification_status.in' => 'Harus termasuk "unconfirmed", "confirmed", "refuted", atau "entered-in-error"',
            'allergy_intolerance.type.in' => 'Harus termasuk "allergy" atau "intolerance"',
            'allergy_intolerance.criticality.in' => 'Harus termasuk "low", "high", atau "unable-to-assess"',
            'allergy_intolerance.onset.onsetAge.comparator.in' => 'Harus termasuk "<", "<=", ">=", atau ">"',

            'identifier.*.use.in' => 'Harus termasuk "usual", "official", "temp", "secondary", atau "old"',

            'reaction.*.reaction_data.severity.in' => 'Harus termasuk "mild", "moderate", atau "severe"',
            'reaction.*.manifestation.*.code.in' => 'Harus termasuk "1985008", "4386001", "9826008", "23924001", "24079001", "31996006", "39579001", "41291007", "43116000", "49727002", "51599000", "62315008", "70076002", "73442001", "76067001", "91175000", "126485001", "162290004", "195967001", "247472004", "267036007", "271757001", "271759003", "271807003", "410430005", "418363000", "422587007", "698247007", "702809001", "768962006"',

            //Untuk decimal
            'allergy_intolerance.onset.onsetAge.value.decimal' => 'Onset age harus berbentuk desimal',
            'allergy_intolerance.onset.onsetRange.low.value.decimal' => 'Low value onset range harus berbentuk desimal',
            'allergy_intolerance.onset.onsetRange.high.value.decimal' => 'High value onset range harus berbentuk desimal'
        ];
    }
}
