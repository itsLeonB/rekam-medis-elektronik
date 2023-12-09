<?php

namespace App\Http\Requests;

use App\Models\MedicationDispense;
use App\Models\MedicationDispensePerformer;
use App\Models\MedicationDispenseSubstitution;
use Illuminate\Validation\Rule;

class MedicationDispenseRequest extends FhirRequest
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
            $this->baseDataRules('medicationDispense.'),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->performerDataRules('performer.*.'),
            $this->getDosageDataRules('dosage.*.'),
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medicationDispense' => 'required|array',
            'identifier' => 'nullable|array',
            'performer' => 'nullable|array',
            'dosage' => 'nullable|array',
        ];
    }

    private function baseDataRules(string $prefix): array
    {
        return array_merge(
            [
                $prefix . 'part_of' => 'nullable|array',
                $prefix . 'part_of.*' => 'sometimes|string',
                $prefix . 'status' => ['required', Rule::in(MedicationDispense::STATUS['binding']['valueset']['code'])],
                $prefix . 'category' => ['nullable', Rule::in(MedicationDispense::CATEGORY['binding']['valueset']['code'])],
                $prefix . 'medication' => 'required|string',
                $prefix . 'subject' => 'required|string',
                $prefix . 'context' => 'nullable|string',
                $prefix . 'location' => 'nullable|string',
                $prefix . 'authorizing_prescription' => 'nullable|array',
                $prefix . 'authorizing_prescription.*' => 'sometimes|string',
                $prefix . 'when_prepared' => 'nullable|date',
                $prefix . 'when_handed_over' => 'nullable|date',
                $prefix . 'substitution_was_substituted' => 'nullable|boolean',
                $prefix . 'substitution_type' => ['nullable', Rule::in(MedicationDispense::SUBSTITUTION_TYPE['binding']['valueset']['code'])],
                $prefix . 'substitution_reason' => 'nullable|array',
                $prefix . 'substitution_reason.*' => ['sometimes', Rule::in(MedicationDispense::SUBSTITUTION_REASON['binding']['valueset']['code'])],
                $prefix . 'substitution_responsible_party' => 'nullable|array',
                $prefix . 'substitution_responsible_party.*' => 'sometimes|string',
            ],
            $this->getQuantityDataRules($prefix . 'quantity_', true),
            $this->getQuantityDataRules($prefix . 'days_supply_', true)
        );
    }

    private function performerDataRules(string $prefix = null): array
    {
        return [
            $prefix . 'function' => ['nullable', Rule::in(MedicationDispensePerformer::FUNCTION['binding']['valueset']['code'])],
            $prefix . 'actor' => 'required|string',
        ];
    }
}
