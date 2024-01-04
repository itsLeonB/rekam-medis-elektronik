<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\LocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Location;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class LocationController extends FhirController
{
    const RESOURCE_TYPE = 'Location';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new LocationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id'] ?? null);
            $processor = new Processor();
            $data = $processor->generateLocation($body);
            $processor->saveLocation($resource, $data);
            $this->createResourceContent(LocationResource::class, $resource);
            return response()->json(new LocationResource($resource), 201);
        });
    }

    public function update(LocationRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Location::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateLocation($resource, $body);
                $this->createResourceContent(LocationResource::class, $resource);
                return response()->json(new LocationResource($resource), 200);
            });
        });
    }
}
