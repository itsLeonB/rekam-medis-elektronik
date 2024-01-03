<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;

class ConditionRequest extends FhirRequest
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
                'clinicalStatus' => 'nullable|array',
                'verificationStatus' => 'nullable|array',
                'category' => 'nullable|array',
                'severity' => 'nullable|array',
                'code' => 'required|array',
                'bodySite' => 'nullable|array',
                'subject' => 'required|array',
                'encounter' => 'required|array',
                'onsetDateTime' => 'nullable|date',
                'onsetAge' => 'nullable|array',
                'onsetPeriod' => 'nullable|array',
                'onsetRange' => 'nullable|array',
                'onsetString' => 'nullable|string',
                'abatementDateTime' => 'nullable|date',
                'abatementAge' => 'nullable|array',
                'abatementPeriod' => 'nullable|array',
                'abatementRange' => 'nullable|array',
                'abatementString' => 'nullable|string',
                'recordedDate' => 'nullable|date',
                'recorder' => 'nullable|array',
                'asserter' => 'nullable|array',
                'stage' => 'nullable|array',
                'stage.*.summary' => 'nullable|array',
                'stage.*.assessment' => 'nullable|array',
                'stage.*.type' => 'nullable|array',
                'evidence' => 'nullable|array',
                'evidence.*.code' => 'nullable|array',
                'evidence.*.detail' => 'nullable|array',
                'note' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodeableConceptRules('clinicalStatus.'),
            $this->getCodeableConceptRules('verificationStatus.'),
            $this->getCodeableConceptRules('category.*.'),
            $this->getCodeableConceptRules('severity.'),
            $this->getCodeableConceptRules('code.'),
            $this->getCodeableConceptRules('bodySite.*.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getAgeRules('onsetAge.'),
            $this->getPeriodRules('onsetPeriod.'),
            $this->getRangeRules('onsetRange.'),
            $this->getAgeRules('abatementAge.'),
            $this->getPeriodRules('abatementPeriod.'),
            $this->getRangeRules('abatementRange.'),
            $this->getReferenceRules('recorder.'),
            $this->getReferenceRules('asserter.'),
            $this->getCodeableConceptRules('stage.*.summary.'),
            $this->getReferenceRules('stage.*.assessment.*.'),
            $this->getCodeableConceptRules('stage.*.type.'),
            $this->getCodeableConceptRules('evidence.*.code.*.'),
            $this->getReferenceRules('evidence.*.detail.*.'),
            $this->getAnnotationRules('note.*.'),
        );
    }
}
