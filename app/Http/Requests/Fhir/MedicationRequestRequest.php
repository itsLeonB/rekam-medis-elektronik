<?php

namespace App\Http\Requests\Fhir;

use App\Http\Requests\FhirRequest;
use App\Models\Fhir\Resources\MedicationRequest;
use Illuminate\Validation\Rule;

class MedicationRequestRequest extends FhirRequest
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
                'status' => ['required', Rule::in(MedicationRequest::STATUS['binding']['valueset']['code'])],
                'statusReason' => 'nullable|array',
                'intent' => ['required', Rule::in(MedicationRequest::INTENT['binding']['valueset']['code'])],
                'category' => 'nullable|array',
                'priority' => ['nullable', Rule::in(MedicationRequest::PRIORITY['binding']['valueset']['code'])],
                'doNotPerform' => 'nullable|boolean',
                'reportedBoolean' => 'nullable|boolean',
                'reportedReference' => 'nullable|array',
                'medicationCodeableConcept' => 'sometimes|array',
                'medicationReference' => 'required|array',
                'subject' => 'required|array',
                'encounter' => 'nullable|array',
                'supportingInformation' => 'nullable|array',
                'authoredOn' => 'nullable|date',
                'requester' => 'nullable|array',
                'performer' => 'nullable|array',
                'performerType' => 'nullable|array',
                'recorder' => 'nullable|array',
                'reasonCode' => 'nullable|array',
                'reasonReference' => 'nullable|array',
                'instantiatesCanonical' => 'nullable|array',
                'instantiatesCanonical.*' => 'nullable|string',
                'instantiatesUri' => 'nullable|array',
                'instantiatesUri.*' => 'nullable|string',
                'basedOn' => 'nullable|array',
                'groupIdentifier' => 'nullable|array',
                'courseOfTherapyType' => 'nullable|array',
                'insurance' => 'nullable|array',
                'note' => 'nullable|array',
                'dosageInstruction' => 'nullable|array',
                'dispenseRequest' => 'nullable|array',
                'dispenseRequest.initialFill' => 'nullable|array',
                'dispenseRequest.initialFill.quantity' => 'nullable|array',
                'dispenseRequest.initialFill.duration' => 'nullable|array',
                'dispenseRequest.dispenseInterval' => 'nullable|array',
                'dispenseRequest.validityPeriod' => 'nullable|array',
                'dispenseRequest.numberOfRepeatsAllowed' => 'nullable|integer|gte:0',
                'dispenseRequest.quantity' => 'nullable|array',
                'dispenseRequest.expectedSupplyDuration' => 'nullable|array',
                'dispenseRequest.performer' => 'nullable|array',
                'substitution' => 'nullable|array',
                'substitution.allowedBoolean' => 'sometimes|boolean',
                'substitution.allowedCodeableConcept' => 'sometimes|array',
                'substitution.reason' => 'nullable|array',
                'priorPrescription' => 'nullable|array',
                'detectedIssue' => 'nullable|array',
                'eventHistory' => 'nullable|array',
            ],
            $this->getIdentifierRules('identifier.*.'),
            $this->getCodeableConceptRules('statusReason.'),
            $this->getCodeableConceptRules('category.*.'),
            $this->getReferenceRules('reportedReference.'),
            $this->getCodeableConceptRules('medicationCodeableConcept.'),
            $this->getReferenceRules('medicationReference.'),
            $this->getReferenceRules('subject.'),
            $this->getReferenceRules('encounter.'),
            $this->getReferenceRules('supportingInformation.*.'),
            $this->getReferenceRules('requester.'),
            $this->getReferenceRules('performer.'),
            $this->getCodeableConceptRules('performerType.'),
            $this->getReferenceRules('recorder.'),
            $this->getCodeableConceptRules('reasonCode.*.'),
            $this->getReferenceRules('reasonReference.*.'),
            $this->getReferenceRules('basedOn.*.'),
            $this->getIdentifierRules('groupIdentifier.'),
            $this->getCodeableConceptRules('courseOfTherapyType.'),
            $this->getReferenceRules('insurance.*.'),
            $this->getCodeableConceptRules('note.*.'),
            $this->getDosageRules('dosageInstruction.*.'),
            $this->getSimpleQuantityRules('dispenseRequest.initialFill.quantity.'),
            $this->getDurationRules('dispenseRequest.initialFill.duration.'),
            $this->getDurationRules('dispenseRequest.dispenseInterval.'),
            $this->getPeriodRules('dispenseRequest.validityPeriod.'),
            $this->getSimpleQuantityRules('dispenseRequest.quantity.'),
            $this->getDurationRules('dispenseRequest.expectedSupplyDuration.'),
            $this->getReferenceRules('dispenseRequest.performer.'),
            $this->getCodeableConceptRules('substitution.allowedCodeableConcept.'),
            $this->getCodeableConceptRules('substitution.reason.'),
            $this->getReferenceRules('priorPrescription.'),
            $this->getReferenceRules('detectedIssue.*.'),
            $this->getReferenceRules('eventHistory.*.')
        );
    }
}
