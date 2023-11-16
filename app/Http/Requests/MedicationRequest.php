<?php

namespace App\Http\Requests;

use App\Models\Medication;
use Illuminate\Validation\Rule;

class MedicationRequest extends FhirRequest
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
            $this->ingredientDataRules()
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medication' => 'required|array',
            'identifier' => 'nullable|array',
            'ingredient' => 'nullable|array'
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('medication.'),
            $this->getRatioDataRules('medication.amount_'),
            [
                'medication.status' => ['nullable', Rule::in(Medication::STATUS_CODE)],
                'medication.manufacturer' => 'nullable|string',
                'medication.form' => ['nullable', Rule::in(Medication::FORM_CODE)],
            ]
        );
    }

    private function ingredientDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('ingredient.*.'),
            $this->getRatioDataRules('ingredient.*.strength_'),
            [
                'ingredient.*.isActive' => 'nullable|boolean',
            ]
        );
    }
}
