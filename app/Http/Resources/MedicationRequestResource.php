<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MedicationRequestResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $medicationRequest = $this->getData('medicationRequest');

        $data = $this->resourceStructure($medicationRequest);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($medicationRequest): array
    {
        return [
            'resourceType' => 'MedicationRequest',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($medicationRequest->identifier, 'createIdentifierResource'),
            'status' => $medicationRequest->status,
            'statusReason' => $this->createCodeableConceptResource($medicationRequest->statusReason),
            'intent' => $medicationRequest->intent,
            'category' => $this->createMany($medicationRequest->category, 'createCodeableConceptResource'),
            'priority' => $medicationRequest->priority,
            'doNotPerform' => $medicationRequest->do_not_perform,
            'reportedBoolean' => $medicationRequest->reported_boolean,
            'reportedReference' => $this->createReferenceResource($medicationRequest->reportedReference),
            'medicationCodeableConcept' => $this->createCodeableConceptResource($medicationRequest->medicationCodeableConcept),
            'medicationReference' => $this->createReferenceResource($medicationRequest->medicationReference),
            'subject' => $this->createReferenceResource($medicationRequest->subject),
            'encounter' => $this->createReferenceResource($medicationRequest->encounter),
            'supportingInformation' => $this->createMany($medicationRequest->supportingInformation, 'createReferenceResource'),
            'authoredOn' => $this->parseDateTime($medicationRequest->authored_on),
            'requester' => $this->createReferenceResource($medicationRequest->requester),
            'performer' => $this->createReferenceResource($medicationRequest->performer),
            'performerType' => $this->createCodeableConceptResource($medicationRequest->performerType),
            'recorder' => $this->createReferenceResource($medicationRequest->recorder),
            'reasonCode' => $this->createMany($medicationRequest->reasonCode, 'createCodeableConceptResource'),
            'reasonReference' => $this->createMany($medicationRequest->reasonReference, 'createReferenceResource'),
            'instantiatesCanonical' => $medicationRequest->instantiates_canonical,
            'instantiatesUri' => $medicationRequest->instantiates_uri,
            'basedOn' => $this->createMany($medicationRequest->basedOn, 'createReferenceResource'),
            'groupIdentifier' => $this->createIdentifierResource($medicationRequest->groupIdentifier),
            'courseOfTherapyType' => $this->createCodeableConceptResource($medicationRequest->courseOfTherapyType),
            'insurance' => $this->createMany($medicationRequest->insurance, 'createReferenceResource'),
            'note' => $this->createMany($medicationRequest->note, 'createAnnotationResource'),
            'dosageInstruction' => $this->createMany($medicationRequest->dosageInstruction, 'createDosageResource'),
            'dispenseRequest' => $this->createDispenseRequestResource($medicationRequest->dispenseRequest),
            'substitution' => $this->createSubstitutionResource($medicationRequest->substitution),
            'priorPrescription' => $this->createReferenceResource($medicationRequest->priorPrescription),
            'detectedIssue' => $this->createMany($medicationRequest->detectedIssue, 'createReferenceResource'),
            'eventHistory' => $this->createMany($medicationRequest->eventHistory, 'createReferenceResource'),
        ];
    }

    public function createDispenseRequestResource($dispenseReq)
    {
        if (!empty($dispenseReq)) {
            return [
                'initialFill' => $this->createInitialFillResource($dispenseReq->initialFill),
                'dispenseInterval' => $this->createDurationResource($dispenseReq->dispenseInterval),
                'validityPeriod' => $this->createPeriodResource($dispenseReq->validityPeriod),
                'numberOfRepeatsAllowed' => $dispenseReq->number_of_repeats_allowed,
                'quantity' => $this->createSimpleQuantityResource($dispenseReq->quantity),
                'expectedSupplyDuration' => $this->createDurationResource($dispenseReq->expectedSupplyDuration),
                'performer' => $this->createReferenceResource($dispenseReq->performer),
            ];
        } else {
            return null;
        }
    }

    public function createInitialFillResource($initialFill)
    {
        if (!empty($initialFill)) {
            return [
                'quantity' => $this->createSimpleQuantityResource($initialFill->quantity),
                'duration' => $this->createDurationResource($initialFill->duration),
            ];
        } else {
            return null;
        }
    }

    public function createSubstitutionResource($subs)
    {
        if (!empty($subs)) {
            return [
                'allowedBoolean' => $subs->allowed_boolean,
                'allowedCodeableConcept' => $this->createCodeableConceptResource($subs->allowedCodeableConcept),
                'reason' => $this->createCodeableConceptResource($subs->reason),
            ];
        } else {
            return null;
        }
    }
}
