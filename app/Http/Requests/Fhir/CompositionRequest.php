<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\BackboneElements\CompositionAttester;
use App\Models\Fhir\BackboneElements\CompositionRelatesTo;
use App\Models\Fhir\BackboneElements\CompositionSection;
use App\Models\Fhir\Resources\Composition;
use Illuminate\Validation\Rule;

class CompositionRequest extends FhirRequest
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
                'status' => ['required', Rule::in(Composition::STATUS['binding']['valueset']['code'])],
                'type' => 'required|array',
                'category' => 'nullable|array',
                'subject' => 'required|array',
                'encounter' => 'nullable|array',
                'date' => 'nullable|date',
                'author' => 'required|array',
                'title' => 'required|string',
                'confidentiality' => ['nullable', Rule::in(Composition::CONFIDENTIALITY['binding']['valueset']['code'])],
                'attester' => 'nullable|array',
                'attester.*.mode' => ['sometimes', Rule::in(CompositionAttester::MODE['binding']['valueset']['code'])],
                'attester.*.time' => 'nullable|date',
                'attester.*.party' => 'nullable|array',
                'custodian' => 'nullable|array',
                'relatesTo' => 'nullable|array',
                'relatesTo.*.code' => ['sometimes', Rule::in(CompositionRelatesTo::CODE['binding']['valueset']['code'])],
                'relatesTo.*.targetIdentifier' => 'sometimes|array',
                'relatesTo.*.targetReference' => 'sometimes|array',
                'event' => 'nullable|array',
                'event.*.code' => 'nullable|array',
                'event.*.period' => 'nullable|array',
                'event.*.detail' => 'nullable|array',
                'section' => 'nullable|array',
                'section.*.title' => 'nullable|string',
                'section.*.code' => 'nullable|array',
                'section.*.author' => 'nullable|array',
                'section.*.focus' => 'nullable|array',
                'section.*.text' => 'nullable|array',
                'section.*.mode' => ['nullable', Rule::in(CompositionSection::MODE['binding']['valueset']['code'])],
                'section.*.orderedBy' => 'nullable|array',
                'section.*.entry' => 'nullable|array',
                'section.*.emptyReason' => 'nullable|array',
                'section.*.section' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.'),
            $this->getCodeableConceptRules('type.'),
            $this->getCodeableConceptRules('category.*.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getReferenceRules('author.*.'),
            $this->getReferenceRules('attester.*.party.'),
            $this->getReferenceRules('custodian.'),
            $this->getReferenceRules('relatesTo.*.targetIdentifier.'),
            $this->getReferenceRules('relatesTo.*.targetReference.'),
            $this->getCodeableConceptRules('event.*.code.*.'),
            $this->getPeriodRules('event.*.period.'),
            $this->getReferenceRules('event.*.detail.*.'),
            $this->getCodeableConceptRules('section.*.code.'),
            $this->getReferenceRules('section.*.author.*.'),
            $this->getReferenceRules('section.*.focus.'),
            $this->getNarrativeRules('section.*.text.'),
            $this->getCodeableConceptRules('section.*.orderedBy.'),
            $this->getReferenceRules('section.*.entry.*.'),
            $this->getCodeableConceptRules('section.*.emptyReason.'),
            $this->getComplexExtensionRules('extension.*.')
        );
    }
}
