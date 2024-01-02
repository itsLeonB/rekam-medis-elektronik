<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\ObservationRequest;
use App\Http\Resources\ObservationResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Observation;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ObservationController extends FhirController
{
    const RESOURCE_TYPE = 'Observation';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new ObservationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(ObservationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateObservation($body);
            $processor->saveObservation($resource, $data);
            $this->createResourceContent(ObservationResource::class, $resource);
            return response()->json(new ObservationResource($resource), 201);
        });
    }

    public function update(ObservationRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Observation::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateObservation($resource, $body);
                $this->createResourceContent(ObservationResource::class, $resource);
                return response()->json(new ObservationResource($resource), 200);
            });
        });
    }
}
