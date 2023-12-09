<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    const RESOURCE_TYPE = 'Location';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new LocationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(LocationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource= $this->createResource('Location');
            $location = $resource->location()->create($body['location']);
            $this->createChildModels($location, $body, ['identifier', 'telecom', 'operationHours']);
            $this->createResourceContent(LocationResource::class, $resource);
            return response()->json($location, 201);
        });
    }


    public function update(LocationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $location = $resource->location()->first();
            $location->update($body['location']);
            $this->updateChildModels($location, $body, ['identifier', 'telecom', 'operationHours']);
            $this->createResourceContent(LocationResource::class, $resource);
            return response()->json($location, 200);
        });
    }
}
