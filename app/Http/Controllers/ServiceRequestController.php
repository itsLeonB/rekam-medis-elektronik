<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequestRequest;
use App\Http\Resources\ServiceRequestResource;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestBasedOn;
use App\Models\ServiceRequestBodySite;
use App\Models\ServiceRequestCategory;
use App\Models\ServiceRequestIdentifier;
use App\Models\ServiceRequestInsurance;
use App\Models\ServiceRequestLocation;
use App\Models\ServiceRequestNote;
use App\Models\ServiceRequestOrderDetail;
use App\Models\ServiceRequestPerformer;
use App\Models\ServiceRequestReason;
use App\Models\ServiceRequestRelevantHistory;
use App\Models\ServiceRequestReplaces;
use App\Models\ServiceRequestSpecimen;
use App\Models\ServiceRequestSupportingInfo;
use App\Services\FhirService;

class ServiceRequestController extends Controller
{
    public function postServiceRequest(ServiceRequestRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            [$resource, $resourceKey] = $this->createResource('ServiceRequest');

            $serviceRequest = ServiceRequest::create(array_merge($resourceKey, $body['service_request']));

            $serviceRequestKey = ['request_id' => $serviceRequest->id];

            $this->createInstances(ServiceRequestIdentifier::class, $serviceRequestKey, $body, 'identifier');
            $this->createInstances(ServiceRequestBasedOn::class, $serviceRequestKey, $body, 'based_on');
            $this->createInstances(ServiceRequestReplaces::class, $serviceRequestKey, $body, 'replaces');
            $this->createInstances(ServiceRequestCategory::class, $serviceRequestKey, $body, 'category');
            $this->createInstances(ServiceRequestOrderDetail::class, $serviceRequestKey, $body, 'order_detail');
            $this->createInstances(ServiceRequestPerformer::class, $serviceRequestKey, $body, 'performer');
            $this->createInstances(ServiceRequestLocation::class, $serviceRequestKey, $body, 'location');
            $this->createInstances(ServiceRequestReason::class, $serviceRequestKey, $body, 'reason');
            $this->createInstances(ServiceRequestInsurance::class, $serviceRequestKey, $body, 'insurance');
            $this->createInstances(ServiceRequestSupportingInfo::class, $serviceRequestKey, $body, 'supporting_info');
            $this->createInstances(ServiceRequestSpecimen::class, $serviceRequestKey, $body, 'specimen');
            $this->createInstances(ServiceRequestBodySite::class, $serviceRequestKey, $body, 'body_site');
            $this->createInstances(ServiceRequestNote::class, $serviceRequestKey, $body, 'note');
            $this->createInstances(ServiceRequestRelevantHistory::class, $serviceRequestKey, $body, 'relevant_history');

            $this->createResourceContent(ServiceRequestResource::class, $resource);

            return response()->json($resource->serviceRequest->first(), 201);
        });
    }
}
