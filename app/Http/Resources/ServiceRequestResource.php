<?php

namespace App\Http\Resources;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'basedOn' => $this->createReferenceArray($serviceRequest->based_on),
                'replaces' => $this->createReferenceArray($serviceRequest->replaces),
                'requisition' => [
                    'system' => $serviceRequest->requisition_value ? $serviceRequest->requisition_system : null,
                    'use' => $serviceRequest->requisition_value ? $serviceRequest->requisition_use : null,
                    'value' => $serviceRequest->requisition_value
                ],
                'status' => $serviceRequest->status,
                'intent' => $serviceRequest->intent,
                'category' => $this->createCategoryArray($serviceRequest->category),
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
                'orderDetail' => $this->createOrderDetailArray($serviceRequest->orderDetail),
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
                            'system' => $serviceRequest->performer_type ? ServiceRequest::PERFORMER_TYPE['binding']['valueset']['system'] : null,
                            'code' => $serviceRequest->performer_type,
                            'display' => $serviceRequest->performer_type ? DB::table(ServiceRequest::PERFORMER_TYPE['binding']['valueset']['table'])
                                ->where('code', $serviceRequest->performer_type)
                                ->value('display') ?? null : null
                        ]
                    ]
                ],
                'performer' => $this->createReferenceArray($serviceRequest->performer),
                'locationCode' => $this->createLocationCodeArray($serviceRequest->location),
                'locationReference' => $this->createReferenceArray($serviceRequest->location),
                'reasonCode' => $this->createReasonCodeArray($serviceRequest->reason),
                'reasonReference' => $this->createReferenceArray($serviceRequest->reason),
                'insurance' => $this->createReferenceArray($serviceRequest->insurance),
                'supportingInfo' => $this->createReferenceArray($serviceRequest->supporting_info),
                'specimen' => $this->createReferenceArray($serviceRequest->specimen),
                'bodySite' => $this->createBodySiteArray($serviceRequest->body_site),
                'note' => $this->createAnnotationArray($serviceRequest->note),
                'patientInstruction' => $serviceRequest->patient_instruction,
                'relevantHistory' => $this->createReferenceArray($serviceRequest->relevant_history)
            ],
            $serviceRequest->quantity,
            $serviceRequest->occurrence,
            $serviceRequest->as_needed
        );
    }


    private function createBodySiteArray($bodySites): array
    {
        $bodySite = [];

        if (!empty($bodySites)) {
            foreach ($bodySites as $bs) {
                $bodySite[] = [
                    'coding' => [
                        [
                            'system' => $bs ? ServiceRequest::BODY_SITE['binding']['valueset']['system'] : null,
                            'code' => $bs,
                            'display' => $bs ? DB::table(ServiceRequest::BODY_SITE['binding']['valueset']['table'])
                                ->where('code', $bs)
                                ->value('display') ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $bodySite;
    }


    private function createReasonCodeArray($reasonCodes): array
    {
        $reasonCode = [];

        if (!empty($reasonCodes)) {
            foreach ($reasonCodes as $rc) {
                $reasonCode[] = [
                    'coding' => [
                        [
                            'system' => $rc ? ServiceRequest::REASON_CODE['binding']['valueset']['system'] : null,
                            'code' => $rc,
                            'display' => $rc ? DB::table(ServiceRequest::REASON_CODE['binding']['valueset']['table'])
                                ->where('code', $rc)
                                ->value('display') ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $reasonCode;
    }


    private function createLocationCodeArray($locationCodes): array
    {
        $locationCode = [];

        if (!empty($locationCodes)) {
            foreach ($locationCodes as $lc) {
                $locationCode[] = [
                    'coding' => [
                        [
                            'system' => $lc ? ServiceRequest::LOCATION_CODE['binding']['valueset']['system'] : null,
                            'code' => $lc,
                            'display' => $lc ? ServiceRequest::LOCATION_CODE['binding']['valueset']['display'][$lc] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $locationCode;
    }


    private function createOrderDetailArray($orderDetails): array
    {
        $orderDetail = [];

        if (!empty($orderDetails)) {
            foreach ($orderDetails as $od) {
                $orderDetail[] = [
                    'coding' => [
                        [
                            'system' => $od ? ServiceRequest::ORDER_DETAIL['binding']['valueset']['system'] : null,
                            'code' => $od,
                            'display' => $od ? ServiceRequest::ORDER_DETAIL['binding']['valueset']['display'][$od] ?? null : null
                        ]
                    ]
                ];
            }
        }

        return $orderDetail;
    }


    private function createCategoryArray($categories): array
    {
        $category = [];

        if (!empty($categories)) {
            foreach ($categories as $c) {
                $category[] = [
                    'coding' => [
                        [
                            'system' => $c ? ServiceRequest::CATEGORY['binding']['valueset']['system'] : null,
                            'code' => $c,
                            'display' => $c ? ServiceRequest::CATEGORY['binding']['valueset']['display'][$c] : null
                        ]
                    ]
                ];
            }
        }

        return $category;
    }
}
