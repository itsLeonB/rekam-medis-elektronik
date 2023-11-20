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
            $this->getReferenceDataRules('partOf.*.'),
            $this->performerDataRules(),
            $this->getReferenceDataRules('authorizingPrescription.*.'),
            $this->getDosageDataRules('dosage.*.'),
            $this->substitutionDataRules()
        );
    }

    private function baseAttributeRules(): array
    {
        return [
            'medicationDispense' => 'required|array',
            'identifier' => 'nullable|array',
            'partOf' => 'nullable|array',
            'performer' => 'nullable|array',
            'authorizingPrescription' => 'nullable|array',
            'dosage' => 'nullable|array',
            'substitution' => 'nullable|array'
        ];
    }

    private function baseDataRules(): array
    {
        return array_merge(
            [
                'medicationDispense.status' => ['required', Rule::in(MedicationDispense::STATUS_CODE)],
                'medicationDispense.category' => ['nullable', Rule::in(MedicationDispense::CATEGORY_CODE)],
                'medicationDispense.medication' => 'required|string',
                'medicationDispense.subject' => 'required|string',
                'medicationDispense.context' => 'nullable|string',
                'medicationDispense.location' => 'nullable|string',
                'medicationDispense.when_prepared' => 'nullable|date',
                'medicationDispense.when_handed_over' => 'nullable|date',
            ],
            $this->getQuantityDataRules('medicationDispense.quantity_', true),
            $this->getQuantityDataRules('medicationDispense.days_supply_', true)
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
                'substitution.*.responsibleParty' => 'nullable|array',
                'substitution.*.substitution_data.was_substituted' => 'required|boolean',
            ],
            $this->getCodeableConceptDataRules('substitution.*.substitution_data.type_', MedicationDispenseSubstitution::TYPE_CODE),
            $this->getCodeableConceptDataRules('substitution.*.reason.*.', MedicationDispenseSubstitution::REASON_CODE),
            $this->getReferenceDataRules('substitution.*.responsibleParty.*.')
        );
    }
}
