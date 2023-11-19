<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequestRequest;
use App\Http\Resources\ServiceRequestResource;
use App\Services\FhirService;

class ServiceRequestController extends Controller
{
    /**
     * Store a new service request.
     *
     * @param ServiceRequestRequest $request The request object.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function store(ServiceRequestRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('ServiceRequest');
            $serviceRequest = $resource->serviceRequest()->create($body['serviceRequest']);
            $this->createChildModels($serviceRequest, $body, ['identifier', 'basedOn', 'replaces', 'category', 'orderDetail', 'performer', 'location', 'reason', 'insurance', 'supportingInfo', 'specimen', 'bodySite', 'note', 'relevantHistory']);
            $this->createResourceContent(ServiceRequestResource::class, $resource);
            return response()->json($resource->serviceRequest->first(), 201);
        });
    }


    /**
     * Update a service request.
     *
     * @param ServiceRequestRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FhirService instance.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(ServiceRequestRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $serviceRequest = $resource->serviceRequest()->first();
            $serviceRequest->update($body['serviceRequest']);
            $requestId = $serviceRequest->id;
            $this->updateChildModels($serviceRequest, $body, ['identifier', 'basedOn', 'replaces', 'category', 'orderDetail', 'performer', 'location', 'reason', 'insurance', 'supportingInfo', 'specimen', 'bodySite', 'note', 'relevantHistory'], 'request_id', $requestId);
            $this->createResourceContent(ServiceRequestResource::class, $resource);
            return response()->json($resource->serviceRequest->first(), 200);
        });
    }
}
