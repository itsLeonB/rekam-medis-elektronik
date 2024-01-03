<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\BackboneElements\AllergyIntoleranceReaction;
use App\Models\Fhir\Resources\AllergyIntolerance;
use Illuminate\Validation\Rule;

class AllergyIntoleranceRequest extends FhirRequest
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
                'type' => ['nullable', Rule::in(AllergyIntolerance::TYPE['binding']['valueset']['code'])],
                'category' => 'required|array',
                'category.*' => ['required', Rule::in(AllergyIntolerance::CATEGORY['binding']['valueset']['code'])],
                'criticality' => ['nullable', Rule::in(AllergyIntolerance::CRITICALITY['binding']['valueset']['code'])],
                'code' => 'required|array',
                'patient' => 'required|array',
                'encounter' => 'nullable|array',
                'onsetDateTime' => 'nullable|date',
                'onsetAge' => 'nullable|array',
                'onsetPeriod' => 'nullable|array',
                'onsetRange' => 'nullable|array',
                'onsetString' => 'nullable|string',
                'recordedDate' => 'nullable|date',
                'recorder' => 'nullable|array',
                'asserter' => 'nullable|array',
                'lastOccurrence' => 'nullable|date',
                'note' => 'nullable|array',
                'reaction' => 'nullable|array',
                'reaction.*.substance' => 'nullable|array',
                'reaction.*.manifestation' => 'sometimes|array',
                'reaction.*.description' => 'nullable|string',
                'reaction.*.onset' => 'nullable|date',
                'reaction.*.severity' => ['nullable', Rule::in(AllergyIntoleranceReaction::SEVERITY['binding']['valueset']['code'])],
                'reaction.*.exposureRoute' => 'nullable|array',
                'reaction.*.note' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodeableConceptRules('clinicalStatus.'),
            $this->getCodeableConceptRules('verificationStatus.'),
            $this->getCodeableConceptRules('code.'),
            $this->getReferenceRules('patient.'),
            $this->getReferenceRules('encounter.'),
            $this->getAgeRules('onsetAge.'),
            $this->getPeriodRules('onsetPeriod.'),
            $this->getRangeRules('onsetRange.'),
            $this->getReferenceRules('recorder.'),
            $this->getReferenceRules('asserter.'),
            $this->getAnnotationRules('note.*.'),
            $this->getCodeableConceptRules('reaction.*.substance.'),
            $this->getCodeableConceptRules('reaction.*.manifestation.*.'),
            $this->getCodeableConceptRules('reaction.*.exposureRoute.'),
            $this->getAnnotationRules('reaction.*.note.*.')
        );
    }
}
