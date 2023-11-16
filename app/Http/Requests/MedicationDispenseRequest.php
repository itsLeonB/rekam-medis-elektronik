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
            $this->baseDataRules(),
            $this->getIdentifierDataRules('identifier.*.'),
            $this->getReferenceDataRules('part_of.*.'),
            $this->performerDataRules(),
            $this->getReferenceDataRules('authorizing_prescription.*.'),
            $this->getDosageDataRules('dosage_instruction.*.'),
            $this->substitutionDataRules()
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medication_dispense' => 'required|array',
            'identifier' => 'nullable|array',
            'part_of' => 'nullable|array',
            'performer' => 'nullable|array',
            'authorizing_prescription' => 'nullable|array',
            'dosage_instruction' => 'nullable|array',
            'substitution' => 'nullable|array'
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'medication_dispense.status' => ['required', Rule::in(MedicationDispense::STATUS_CODE)],
                'medication_dispense.category' => ['nullable', Rule::in(MedicationDispense::CATEGORY_CODE)],
                'medication_dispense.medication' => 'required|string',
                'medication_dispense.subject' => 'required|string',
                'medication_dispense.context' => 'nullable|string',
                'medication_dispense.location' => 'nullable|string',
                'medication_dispense.when_prepared' => 'nullable|date',
                'medication_dispense.when_handed_over' => 'nullable|date',
            ],
            $this->getQuantityDataRules('medication_dispense.quantity_', true),
            $this->getQuantityDataRules('medication_dispense.days_supply_', true)
        );
    }

    private function performerDataRules(): array
    {
        return array_merge(
            $this->getCodeableConceptDataRules('performer.*.function_', MedicationDispensePerformer::FUNCTION_CODE),
            [
                'performer.*.actor' => 'required|string',
            ]
        );
    }

    private function substitutionDataRules(): array
    {
        return array_merge(
            [
                'substitution.*.substitution_data' => 'required|array',
                'substitution.*.reason' => 'nullable|array',
                'substitution.*.responsible_party' => 'nullable|array',
                'substitution.*.substitution_data.was_substituted' => 'required|boolean',
            ],
            $this->getCodeableConceptDataRules('substitution.*.substitution_data.type_', MedicationDispenseSubstitution::TYPE_CODE),
            $this->getCodeableConceptDataRules('substitution.*.reason.*.', MedicationDispenseSubstitution::REASON_CODE),
            $this->getReferenceDataRules('substitution.*.responsible_party.*.')
        );
    }
}
