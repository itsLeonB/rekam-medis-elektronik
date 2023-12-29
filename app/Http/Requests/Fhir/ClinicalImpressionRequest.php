<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\ClinicalImpression;
use Illuminate\Validation\Rule;

class ClinicalImpressionRequest extends FhirRequest
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
                'status' => ['required', Rule::in(ClinicalImpression::STATUS['binding']['valueset']['code'])],
                'statusReason' => 'nullable|array',
                'code' => 'nullable|array',
                'description' => 'nullable|string',
                'subject' => 'required|array',
                'encounter' => 'required|array',
                'effectiveDateTime' => 'nullable|date',
                'effectivePeriod' => 'nullable|array',
                'date' => 'nullable|date',
                'assessor' => 'nullable|array',
                'previous' => 'nullable|array',
                'problem' => 'nullable|array',
                'investigation' => 'nullable|array',
                'investigation.*.code' => 'sometimes|array',
                'investigation.*.item' => 'nullable|array',
                'protocol' => 'nullable|array',
                'protocol.*' => 'nullable|string',
                'summary' => 'nullable|string',
                'finding' => 'nullable|array',
                'finding.*.itemCodeableConcept' => 'nullable|array',
                'finding.*.itemReference' => 'nullable|array',
                'finding.*.basis' => 'nullable|array',
                'prognosisCodeableConcept' => 'required|array',
                'prognosisReference' => 'nullable|array',
                'supportingInfo' => 'nullable|array',
                'note' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodeableConceptRules('statusReason.'),
            $this->getCodeableConceptRules('code.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getPeriodRules('effectivePeriod.'),
            $this->getReferenceRules('assessor.'),
            $this->getReferenceRules('previous.'),
            $this->getReferenceRules('problem.*.'),
            $this->getCodeableConceptRules('investigation.*.code.'),
            $this->getReferenceRules('investigation.*.item.*.'),
            $this->getCodeableConceptRules('finding.*.itemCodeableConcept.'),
            $this->getReferenceRules('finding.*.itemReference.'),
            $this->getCodeableConceptRules('prognosisCodeableConcept.*.'),
            $this->getReferenceRules('prognosisReference.*.'),
            $this->getReferenceRules('supportingInfo.*.'),
            $this->getAnnotationRules('note.*.')
        );
    }
}
