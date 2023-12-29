<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\MedicationStatement;
use Illuminate\Validation\Rule;

class MedicationStatementRequest extends FhirRequest
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
                'basedOn' => 'nullable|array',
                'partOf' => 'nullable|array',
                'status' => ['required', Rule::in(MedicationStatement::STATUS['binding']['valueset']['code'])],
                'statusReason' => 'nullable|array',
                'category' => 'nullable|array',
                'medicationCodeableConcept' => 'sometimes|array',
                'medicationReference' => 'sometimes|array',
                'subject' => 'required|array',
                'context' => 'nullable|array',
                'effectiveDateTime' => 'nullable|date',
                'effectivePeriod' => 'nullable|array',
                'dateAsserted' => 'nullable|date',
                'informationSource' => 'nullable|array',
                'derivedFrom' => 'nullable|array',
                'reasonCode' => 'nullable|array',
                'reasonReference' => 'nullable|array',
                'note' => 'nullable|array',
                'dosage' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getReferenceRules('basedOn.*.'),
            $this->getReferenceRules('partOf.*.'),
            $this->getCodeableConceptRules('statusReason.*.'),
            $this->getCodeableConceptRules('category.'),
            $this->getCodeableConceptRules('medicationCodeableConcept.'),
            $this->getReferenceRules('medicationReference.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('context.'),
            $this->getPeriodRules('effectivePeriod.'),
            $this->getReferenceRules('informationSource.'),
            $this->getReferenceRules('derivedFrom.*.'),
            $this->getCodeableConceptRules('reasonCode.*.'),
            $this->getReferenceRules('reasonReference.*.'),
            $this->getAnnotationRules('note.*.'),
            $this->getDosageRules('dosage.*.'),
        );
    }
}
