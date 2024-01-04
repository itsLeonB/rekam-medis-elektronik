<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\ServiceRequestRequest;
use App\Http\Resources\ServiceRequestResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\ServiceRequest;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServiceRequestController extends FhirController
{
    const RESOURCE_TYPE = 'ServiceRequest';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new ServiceRequestResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id'] ?? null);
            $processor = new Processor();
            $data = $processor->generateServiceRequest($body);
            $saved = $processor->saveServiceRequest($resource, $data);
            $this->createResourceContent(ServiceRequestResource::class, $resource);
            return response()->json(new ServiceRequestResource($resource), 201);
        });
    }

    public function update(ServiceRequestRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return ServiceRequest::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateServiceRequest($resource, $body);
                $this->createResourceContent(ServiceRequestResource::class, $resource);
                return response()->json(new ServiceRequestResource($resource), 200);
            });
        });
    }
}
