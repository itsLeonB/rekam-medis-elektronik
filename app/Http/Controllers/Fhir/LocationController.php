<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Services\FhirService;

class LocationController extends Controller
{
    public function store(LocationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource= $this->createResource('Location');
            $location = $resource->location()->create($body['location']);
            $this->createChildModels($location, $body, ['identifier', 'telecom', 'operationHours']);
            $this->createResourceContent(LocationResource::class, $resource);
            return response()->json($resource->location()->first(), 201);
        });
    }


    public function update(LocationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $location = $resource->location()->first();
            $location->update($body['location']);
            $locationId = $location->id;
            $this->updateChildModels($location, $body, ['identifier', 'telecom', 'operationHours'], 'location_id', $locationId);
            $this->createResourceContent(LocationResource::class, $resource);
            return response()->json($resource->location()->first(), 200);
        });
    }
}
