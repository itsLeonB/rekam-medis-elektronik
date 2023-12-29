<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\Medication;
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
            [
                'identifier' => 'nullable|array',
                'code' => 'nullable|array',
                'status' => ['nullable', Rule::in(Medication::STATUS['binding']['valueset']['code'])],
                'manufacturer' => 'nullable|array',
                'form' => 'nullable|array',
                'amount' => 'nullable|array',
                'ingredient' => 'nullable|array',
                'ingredient.*.itemCodeableConcept' => 'sometimes|array',
                'ingredient.*.itemReference' => 'sometimes|array',
                'ingredient.*.isActive' => 'nullable|boolean',
                'ingredient.*.strength' => 'nullable|array',
                'batch' => 'nullable|array',
                'batch.lotNumber' => 'nullable|string',
                'batch.expirationDate' => 'nullable|date',
                'extension' => 'required|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodeableConceptRules('code.'),
            $this->getReferenceRules('manufacturer.'),
            $this->getCodeableConceptRules('form.'),
            $this->getRatioRules('amount.'),
            $this->getCodeableConceptRules('ingredient.*.itemCodeableConcept.'),
            $this->getReferenceRules('ingredient.*.itemReference.'),
            $this->getRatioRules('ingredient.*.strength.'),
            $this->getExtensionRules('extension.*.')
        );
    }
}
