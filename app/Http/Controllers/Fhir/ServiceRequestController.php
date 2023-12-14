<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\ServiceRequestRequest;
use App\Http\Resources\ServiceRequestResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ServiceRequestController extends FhirController
{
    const RESOURCE_TYPE = 'ServiceRequest';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new ServiceRequestResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(ServiceRequestRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $serviceRequest = $resource->serviceRequest()->create($body['serviceRequest']);
            $this->createChildModels($serviceRequest, $body, ['identifier', 'note']);
            $this->createResourceContent(ServiceRequestResource::class, $resource);
            return response()->json($serviceRequest, 201);
        });
    }


    public function update(ServiceRequestRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $serviceRequest = $resource->serviceRequest()->first();
            $serviceRequest->update($body['serviceRequest']);
            $this->updateChildModels($serviceRequest, $body, ['identifier', 'note']);
            $this->createResourceContent(ServiceRequestResource::class, $resource);
            return response()->json($serviceRequest, 200);
        });
    }
}
