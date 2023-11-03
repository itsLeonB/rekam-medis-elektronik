<?php

namespace App\Http\Requests;

use App\Models\Age;
use App\Models\Condition;
use App\Models\ConditionCategory;
use App\Models\ConditionIdentifier;
use App\Models\Identifier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ConditionRequest extends FormRequest
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
            // Condition attributes
            'condition' => 'required|array',
            'identifier' => 'nullable|array',
            'category' => 'nullable|array',
            'body_site' => 'nullable|array',
            'stage' => 'nullable|array',
            'evidence' => 'nullable|array',
            'note' => 'nullable|array',

            // Condition base data
            'condition.clinical_status' => ['nullable', Rule::in(Condition::CLINICAL_STATUS)],
            'condition.verification_status' => ['nullable', Rule::in(Condition::VERIFICATION_STATUS)],
            'condition.severity' => ['nullable', Rule::in(Condition::SEVERITY)],
            'condition.code' => 'required|string|exists:codesystem_icd10,code',
            'condition.subject' => 'required|string',
            'condition.encounter' => 'required|string',
            'condition.onset' => 'nullable|array',
            'condition.onset.onsetDateTime' => 'nullable|date',
            'condition.onset.onsetAge' => 'nullable|array',
            'condition.onset.onsetAge.value' => 'nullable|decimal',
            'condition.onset.onsetAge.comparator' => ['nullable', Rule::in(Age::COMPARATOR)],
            'condition.onset.onsetAge.unit' => 'nullable|string',
            'condition.onset.onsetAge.system' => 'nullable|string',
            'condition.onset.onsetAge.code' => 'nullable|string',
            'condition.onset.onsetPeriod' => 'nullable|array',
            'condition.onset.onsetPeriod.start' => 'nullable|date',
            'condition.onset.onsetPeriod.end' => 'nullable|date',
            'condition.onset.onsetRange' => 'nullable|array',
            'condition.onset.onsetRange.low' => 'nullable|array',
            'condition.onset.onsetRange.low.value' => 'nullable|decimal',
            'condition.onset.onsetRange.low.unit' => 'nullable|string',
            'condition.onset.onsetRange.low.system' => 'nullable|string',
            'condition.onset.onsetRange.low.code' => 'nullable|string',
            'condition.onset.onsetRange.high' => 'nullable|array',
            'condition.onset.onsetRange.high.value' => 'nullable|decimal',
            'condition.onset.onsetRange.high.unit' => 'nullable|string',
            'condition.onset.onsetRange.high.system' => 'nullable|string',
            'condition.onset.onsetRange.high.code' => 'nullable|string',
            'condition.onset.onsetString' => 'nullable|string',
            'condition.abatement' => 'nullable|array',
            'condition.abatement.abatementDateTime' => 'nullable|date',
            'condition.abatement.abatementAge' => 'nullable|array',
            'condition.abatement.abatementAge.value' => 'nullable|decimal',
            'condition.abatement.abatementAge.comparator' => ['nullable', Rule::in(Age::COMPARATOR)],
            'condition.abatement.abatementAge.unit' => 'nullable|string',
            'condition.abatement.abatementAge.system' => 'nullable|string',
            'condition.abatement.abatementAge.code' => 'nullable|string',
            'condition.abatement.abatementPeriod' => 'nullable|array',
            'condition.abatement.abatementPeriod.start' => 'nullable|date',
            'condition.abatement.abatementPeriod.end' => 'nullable|date',
            'condition.abatement.abatementRange' => 'nullable|array',
            'condition.abatement.abatementRange.low' => 'nullable|array',
            'condition.abatement.abatementRange.low.value' => 'nullable|decimal',
            'condition.abatement.abatementRange.low.unit' => 'nullable|string',
            'condition.abatement.abatementRange.low.system' => 'nullable|string',
            'condition.abatement.abatementRange.low.code' => 'nullable|string',
            'condition.abatement.abatementRange.high' => 'nullable|array',
            'condition.abatement.abatementRange.high.value' => 'nullable|decimal',
            'condition.abatement.abatementRange.high.unit' => 'nullable|string',
            'condition.abatement.abatementRange.high.system' => 'nullable|string',
            'condition.abatement.abatementRange.high.code' => 'nullable|string',
            'condition.abatement.abatementString' => 'nullable|string',
            'condition.recorded_date' => 'nullable|date',
            'condition.recorder' => 'nullable|string',
            'condition.asserter' => 'nullable|string',

            // Condition identifier data
            'identifier.*.system' => 'required|string',
            'identifier.*.use' => ['required', Rule::in(ConditionIdentifier::USE)],
            'identifier.*.value' => 'required|string',

            // Condition category data
            'category.*.system' => 'required|string',
            'category.*.code' => ['required', Rule::in(ConditionCategory::CODE)],
            'category.*.display' => 'required|string',

            // Condition body site data
            'body_site.*.system' => 'required|string',
            'body_site.*.code' => 'required|string',
            'body_site.*.display' => 'required|string',

            // Condition stage data
            'stage.*.stage_data' => 'required|array',
            'stage.*.stage_data.summary_system' => 'nullable|string',
            'stage.*.stage_data.summary_code' => 'nullable|string',
            'stage.*.stage_data.summary_display' => 'nullable|string',
            'stage.*.stage_data.type_system' => 'nullable|string',
            'stage.*.stage_data.type_code' => 'nullable|string',
            'stage.*.stage_data.type_display' => 'nullable|string',
            'stage.*.assessment' => 'nullable|array',
            'stage.*.assessment.*.reference' => 'required|string',

            // Condition evidence data
            'evidence.*.system' => 'nullable|string',
            'evidence.*.code' => 'nullable|integer|gte:0',
            'evidence.*.display' => 'nullable|string',
            'evidence.*.detail_reference' => 'nullable|string',

            // Condition note data
            'note.*.author' => 'nullable|array',
            'note.*.author.authorString' => 'nullable|string',
            'note.*.author.authorReference' => 'nullable|array',
            'note.*.author.authorReference.reference' => 'nullable|string',
            'note.*.time' => 'nullable|date',
            'note.*.text' => 'nullable|string',
        ];
    }
}
