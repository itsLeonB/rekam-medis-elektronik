<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\Procedure;
use Illuminate\Validation\Rule;

class ProcedureRequest extends FhirRequest
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
                'instantiatesCanonical' => 'nullable|array',
                'instantiatesUri' => 'nullable|array',
                'basedOn' => 'nullable|array',
                'partOf' => 'nullable|array',
                'status' => ['required', Rule::in(Procedure::STATUS['binding']['valueset']['code'])],
                'statusReason' => 'nullable|array',
                'category' => 'nullable|array',
                'code' => 'required|array',
                'subject' => 'required|array',
                'encounter' => 'required|array',
                'performedDateTime' => 'nullable|date',
                'performedPeriod' => 'nullable|array',
                'performedString' => 'nullable|string',
                'performedAge' => 'nullable|array',
                'performedRange' => 'nullable|array',
                'recorder' => 'nullable|array',
                'asserter' => 'nullable|array',
                'performer' => 'nullable|array',
                'performer.*.function' => 'nullable|array',
                'performer.*.actor' => 'sometimes|array',
                'performer.*.onBehalfOf' => 'nullable|array',
                'location' => 'nullable|array',
                'reasonCode' => 'nullable|array',
                'reasonReference' => 'nullable|array',
                'bodySite' => 'nullable|array',
                'outcome' => 'nullable|array',
                'report' => 'nullable|array',
                'complication' => 'nullable|array',
                'complicationDetail' => 'nullable|array',
                'followUp' => 'nullable|array',
                'note' => 'nullable|array',
                'focalDevice' => 'nullable|array',
                'focalDevice.*.action' => 'nullable|array',
                'focalDevice.*.manipulated' => 'sometimes|array',
                'usedReference' => 'nullable|array',
                'usedCode' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getReferenceRules('basedOn.*.'),
            $this->getReferenceRules('partOf.*.'),
            $this->getCodeableConceptRules('statusReason.'),
            $this->getCodeableConceptRules('category.'),
            $this->getCodeableConceptRules('code.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getPeriodRules('performedPeriod.'),
            $this->getAgeRules('performedAge.'),
            $this->getRangeRules('performedRange.'),
            $this->getReferenceRules('recorder.'),
            $this->getReferenceRules('asserter.'),
            $this->getCodeableConceptRules('performer.*.function.'),
            $this->getReferenceRules('performer.*.actor.'),
            $this->getReferenceRules('performer.*.onBehalfOf.'),
            $this->getReferenceRules('location.'),
            $this->getCodeableConceptRules('reasonCode.*.'),
            $this->getReferenceRules('reasonReference.*.'),
            $this->getCodeableConceptRules('bodySite.*.'),
            $this->getCodeableConceptRules('outcome.'),
            $this->getReferenceRules('report.*.'),
            $this->getCodeableConceptRules('complication.*.'),
            $this->getReferenceRules('complicationDetail.*.'),
            $this->getCodeableConceptRules('followUp.*.'),
            $this->getAnnotationRules('note.*.'),
            $this->getCodeableConceptRules('focalDevice.*.action.'),
            $this->getReferenceRules('focalDevice.*.manipulated.'),
            $this->getReferenceRules('usedReference.*.'),
            $this->getCodeableConceptRules('usedCode.*.'),
        );
    }
}
