<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Medication;
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
            $this->baseDataRules('medication.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->ingredientDataRules('ingredient.*.')
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

    private function baseDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'system' => 'nullable|string',
                $prefix . 'code' => 'nullable|string',
                $prefix . 'display' => 'nullable|string',
                $prefix . 'status' => ['nullable', Rule::in(Medication::STATUS['binding']['valueset']['code'])],
                $prefix . 'manufacturer' => 'nullable|string',
                $prefix . 'form' => ['nullable', Rule::in(Medication::FORM['binding']['valueset']['code'])],
            ],
            $this->getRatioDataRules($prefix . 'amount_'),
        );
    }

    private function ingredientDataRules($prefix): array
    {
        return array_merge(
            [
                $prefix . 'system' => 'nullable|string',
                $prefix . 'code' => 'nullable|string',
                $prefix . 'display' => 'nullable|string',
                $prefix . 'is_active' => 'nullable|boolean',
            ],
            $this->getRatioDataRules($prefix . 'strength_'),
        );
    }
}
