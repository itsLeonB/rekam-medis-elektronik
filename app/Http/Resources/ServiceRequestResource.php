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

        $data = removeEmptyValues($data);

        return $data;
    }

    private function resourceStructure($serviceRequest): array
    {
        return merge_array(
            [
                'resourceType' => 'ServiceRequest',
                'id' => $this->satusehat_id,
                'identifier' => $this->createIdentifierArray($serviceRequest->identifier),
                'basedOn' => $this->createReferenceArray($serviceRequest->basedOn),
                'replaces' => $this->createReferenceArray($serviceRequest->replaces),
                'requisition' => [
                    'system' => $serviceRequest->requisition_value ? $serviceRequest->requisition_system : null,
                    'use' => $serviceRequest->requisition_value ? $serviceRequest->requisition_use : null,
                    'value' => $serviceRequest->requisition_value
                ],
                'status' => $serviceRequest->status,
                'intent' => $serviceRequest->intent,
                'category' => $this->createCodeableConceptArray($serviceRequest->category),
                'priority' => $serviceRequest->priority,
                'doNotPerform' => $serviceRequest->do_not_perform,
                'code' => [
                    'coding' => [
                        [
                            'system' => $serviceRequest->code_system,
                            'code' => $serviceRequest->code_code,
                            'display' => $serviceRequest->code_display
                        ]
                    ]
                ],
                'orderDetail' => $this->createCodeableConceptArray($serviceRequest->orderDetail),
                'subject' => [
                    'reference' => $serviceRequest->subject
                ],
                'encounter' => [
                    'reference' => $serviceRequest->encounter
                ],
                'authoredOn' => $this->parseDateFhir($serviceRequest->authored_on),
                'requester' => [
                    'reference' => $serviceRequest->requester
                ],
                'performerType' => [
                    'coding' => [
                        [
                            'system' => $serviceRequest->performer_type_system,
                            'code' => $serviceRequest->performer_type_code,
                            'display' => $serviceRequest->performer_type_display
                        ]
                    ]
                ],
                'performer' => $this->createReferenceArray($serviceRequest->performer),
                'locationCode' => $this->createCodeableConceptArray($serviceRequest->location),
                'locationReference' => $this->createReferenceArray($serviceRequest->location),
                'reasonCode' => $this->createCodeableConceptArray($serviceRequest->reason),
                'reasonReference' => $this->createReferenceArray($serviceRequest->reason),
                'insurance' => $this->createReferenceArray($serviceRequest->insurance),
                'supportingInfo' => $this->createReferenceArray($serviceRequest->supportingInfo),
                'specimen' => $this->createReferenceArray($serviceRequest->specimen),
                'bodySite' => $this->createCodeableConceptArray($serviceRequest->bodySite),
                'note' => $this->createAnnotationArray($serviceRequest->note),
                'patientInstruction' => $serviceRequest->patient_instruction,
                'relevantHistory' => $this->createReferenceArray($serviceRequest->relevantHistory)
            ],
            $serviceRequest->quantity,
            $serviceRequest->occurrence,
            $serviceRequest->as_needed
        );
    }
}
