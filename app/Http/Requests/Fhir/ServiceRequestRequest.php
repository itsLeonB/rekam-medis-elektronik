<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\ServiceRequest;
use Illuminate\Validation\Rule;

class ServiceRequestRequest extends FhirRequest
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
                'instantiatesCanonical.*' => 'sometimes|string',
                'instantiatesUri' => 'nullable|array',
                'instantiatesUri.*' => 'sometimes|url',
                'basedOn' => 'nullable|array',
                'replaces' => 'nullable|array',
                'requisition' => 'nullable|array',
                'status' => ['required', Rule::in(ServiceRequest::STATUS['binding']['valueset']['code'])],
                'intent' => ['required', Rule::in(ServiceRequest::INTENT['binding']['valueset']['code'])],
                'category' => 'nullable|array',
                'priority' => ['nullable', Rule::in(ServiceRequest::PRIORITY['binding']['valueset']['code'])],
                'doNotPerform' => 'nullable|boolean',
                'code' => 'required|array',
                'orderDetail' => 'nullable|array',
                'quantityQuantity' => 'nullable|array',
                'quantityRatio' => 'nullable|array',
                'quantityRange' => 'nullable|array',
                'subject' => 'required|array',
                'encounter' => 'required|array',
                'occurrenceDateTime' => 'nullable|date',
                'occurrencePeriod' => 'nullable|array',
                'occurrenceTiming' => 'nullable|array',
                'asNeededBoolean' => 'nullable|boolean',
                'asNeededCodeableConcept' => 'nullable|array',
                'authoredOn' => 'nullable|date',
                'requester' => 'nullable|array',
                'performerType' => 'nullable|array',
                'performer' => 'nullable|array',
                'locationCode' => 'nullable|array',
                'locationReference' => 'nullable|array',
                'reasonCode' => 'nullable|array',
                'reasonReference' => 'nullable|array',
                'insurance' => 'nullable|array',
                'supportingInfo' => 'nullable|array',
                'specimen' => 'nullable|array',
                'bodySite' => 'nullable|array',
                'note' => 'nullable|array',
                'patientInstruction' => 'nullable|string',
                'relevantHistory' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getReferenceRules('basedOn.*.'),
            $this->getReferenceRules('replaces.*.'),
            $this->getIdentifierRules('requisition.'),
            $this->getCodeableConceptRules('category.*.'),
            $this->getCodeableConceptRules('code.'),
            $this->getCodeableConceptRules('orderDetail.*.'),
            $this->getQuantityRules('quantityQuantity.'),
            $this->getRatioRules('quantityRatio.'),
            $this->getRangeRules('quantityRange.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getPeriodRules('occurrencePeriod.'),
            $this->getReferenceRules('occurrenceTiming.'),
            $this->getCodeableConceptRules('asNeededCodeableConcept.'),
            $this->getReferenceRules('requester.'),
            $this->getCodeableConceptRules('performerType.'),
            $this->getReferenceRules('performer.*.'),
            $this->getCodeableConceptRules('locationCode.*.'),
            $this->getReferenceRules('locationReference.*.'),
            $this->getCodeableConceptRules('reasonCode.*.'),
            $this->getReferenceRules('reasonReference.*.'),
            $this->getReferenceRules('insurance.*.'),
            $this->getReferenceRules('supportingInfo.*.'),
            $this->getReferenceRules('specimen.*.'),
            $this->getCodeableConceptRules('bodySite.*.'),
            $this->getAnnotationRules('note.*.'),
            $this->getReferenceRules('relevantHistory.*.')
        );
    }
}
