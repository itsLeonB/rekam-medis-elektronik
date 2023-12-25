<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ServiceRequestResource extends FhirResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $serviceRequest = $this->getData('servicerequest');

        $data = $this->resourceStructure($serviceRequest);

        $data = $this->removeEmptyValues($data);

        return $data;
    }

    public function resourceStructure($serviceRequest): array
    {
        return [
            'resourceType' => 'ServiceRequest',
            'id' => $this->satusehat_id,
            'identifier' => $this->createMany($serviceRequest->identifier, 'createIdentifierResource'),
            'instantiatesCanonical' => $serviceRequest->instantiates_canonical,
            'instantiatesUri' => $serviceRequest->instantiates_uri,
            'basedOn' => $this->createMany($serviceRequest->basedOn, 'createReferenceResource'),
            'replaces' => $this->createMany($serviceRequest->replaces, 'createReferenceResource'),
            'requisition' => $this->createIdentifierResource($serviceRequest->requisition),
            'status' => $serviceRequest->status,
            'intent' => $serviceRequest->intent,
            'category' => $this->createMany($serviceRequest->category, 'createCodeableConceptResource'),
            'priority' => $serviceRequest->priority,
            'doNotPerform' => $serviceRequest->do_not_perform,
            'code' => $this->createCodeableConceptResource($serviceRequest->code),
            'orderDetail' => $this->createMany($serviceRequest->orderDetail, 'createCodeableConceptResource'),
            'quantityQuantity' => $this->createQuantityResource($serviceRequest->quantityQuantity),
            'quantityRatio' => $this->createRatioResource($serviceRequest->quantityRatio),
            'quantityRange' => $this->createRangeResource($serviceRequest->quantityRange),
            'subject' => $this->createReferenceResource($serviceRequest->subject),
            'encounter' => $this->createReferenceResource($serviceRequest->encounter),
            'occurrenceDateTime' => $this->parseDateTime($serviceRequest->occurrence_date_time),
            'occurrencePeriod' => $this->createPeriodResource($serviceRequest->occurrence_period),
            'occurrenceTiming' => $this->createTimingResource($serviceRequest->occurrence_timing),
            'asNeededBoolean' => $serviceRequest->as_needed_boolean,
            'asNeededCodeableConcept' => $this->createCodeableConceptResource($serviceRequest->as_needed_codeable_concept),
            'authoredOn' => $this->parseDateTime($serviceRequest->authored_on),
            'requester' => $this->createReferenceResource($serviceRequest->requester),
            'performerType' => $this->createCodeableConceptResource($serviceRequest->performer_type),
            'performer' => $this->createMany($serviceRequest->performer, 'createReferenceResource'),
            'locationCode' => $this->createMany($serviceRequest->location_code, 'createCodeableConceptResource'),
            'locationReference' => $this->createMany($serviceRequest->location_reference, 'createReferenceResource'),
            'reasonCode' => $this->createMany($serviceRequest->reason_code, 'createCodeableConceptResource'),
            'reasonReference' => $this->createMany($serviceRequest->reason_reference, 'createReferenceResource'),
            'insurance' => $this->createMany($serviceRequest->insurance, 'createReferenceResource'),
            'supportingInfo' => $this->createMany($serviceRequest->supporting_info, 'createReferenceResource'),
            'specimen' => $this->createMany($serviceRequest->specimen, 'createReferenceResource'),
            'bodySite' => $this->createMany($serviceRequest->body_site, 'createCodeableConceptResource'),
            'note' => $this->createMany($serviceRequest->note, 'createAnnotationResource'),
            'patientInstruction' => $serviceRequest->patient_instruction,
            'relevantHistory' => $this->createMany($serviceRequest->relevant_history, 'createReferenceResource'),
        ];
    }
}
