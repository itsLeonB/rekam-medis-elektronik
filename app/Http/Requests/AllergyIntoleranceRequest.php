<?php

namespace App\Http\Requests;

use App\Models\Age;
use App\Models\AllergyIntolerance;
use App\Models\AllergyIntoleranceReaction;
use App\Models\AllergyIntoleranceReactionManifestation;
use App\Models\Identifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AllergyIntoleranceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if (!Auth::check()) {
        //     abort(403, 'Unauthorized action.');
        // }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // AllergyIntolerance base attributes
            'allergy_intolerance' => 'required|array',
            'identifier' => 'nullable|array',
            'note' => 'nullable|array',
            'reaction' => 'nullable|array',

            // AllergyIntolerance base data
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
            'allergy_intolerance.onset' => 'nullable|array',
            'allergy_intolerance.onset.onsetDateTime' => 'nullable|date',
            'allergy_intolerance.onset.onsetAge' => 'nullable|array',
            'allergy_intolerance.onset.onsetAge.value' => 'nullable|decimal',
            'allergy_intolerance.onset.onsetAge.comparator' => ['nullable', Rule::in(Age::COMPARATOR)],
            'allergy_intolerance.onset.onsetAge.unit' => 'nullable|string',
            'allergy_intolerance.onset.onsetAge.system' => 'nullable|string',
            'allergy_intolerance.onset.onsetAge.code' => 'nullable|string',
            'allergy_intolerance.onset.onsetPeriod' => 'nullable|array',
            'allergy_intolerance.onset.onsetPeriod.start' => 'nullable|date',
            'allergy_intolerance.onset.onsetRange' => 'nullable|array',
            'allergy_intolerance.onset.onsetRange.low' => 'nullable|array',
            'allergy_intolerance.onset.onsetRange.low.value' => 'nullable|decimal',
            'allergy_intolerance.onset.onsetRange.low.unit' => 'nullable|string',
            'allergy_intolerance.onset.onsetRange.low.system' => 'nullable|string',
            'allergy_intolerance.onset.onsetRange.low.code' => 'nullable|string',
            'allergy_intolerance.onset.onsetRange.high' => 'nullable|array',
            'allergy_intolerance.onset.onsetRange.high.value' => 'nullable|decimal',
            'allergy_intolerance.onset.onsetRange.high.unit' => 'nullable|string',
            'allergy_intolerance.onset.onsetRange.high.system' => 'nullable|string',
            'allergy_intolerance.onset.onsetRange.high.code' => 'nullable|string',
            'allergy_intolerance.onset.onsetString' => 'nullable|string',
            'allergy_intolerance.recorded_date' => 'nullable|date',
            'allergy_intolerance.recorder' => 'nullable|string',
            'allergy_intolerance.asserter' => 'nullable|string',
            'allergy_intolerance.last_occurence' => 'nullable|date',

            // AllergyIntolerance identifier data
            'identifier.*.system' => 'required|string',
            'identifier.*.use' => ['required', Rule::in(Identifier::USE)],
            'identifier.*.value' => 'required|string',

            // AllergyIntolerance note data
            'note.*.author' => 'nullable|array',
            'note.*.author.authorString' => 'nullable|string',
            'note.*.author.authorReference' => 'nullable|array',
            'note.*.author.authorReference.reference' => 'nullable|string',
            'note.*.time' => 'nullable|date',
            'note.*.text' => 'required|string',

            // AllergyIntolerance reaction data
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
            'reaction.*.note' => 'nullable|array',
            'reaction.*.note.*.author' => 'nullable|array',
            'reaction.*.note.*.author.authorString' => 'nullable|string',
            'reaction.*.note.*.author.authorReference' => 'nullable|array',
            'reaction.*.note.*.author.authorReference.reference' => 'nullable|string',
            'reaction.*.note.*.time' => 'nullable|date',
            'reaction.*.note.*.text' => 'required|string',
        ];
    }
}
