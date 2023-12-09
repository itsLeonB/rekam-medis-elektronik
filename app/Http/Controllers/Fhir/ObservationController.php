<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\ObservationRequest;
use App\Http\Resources\ObservationResource;
use App\Models\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ObservationController extends Controller
{
    const RESOURCE_TYPE = 'Observation';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new ObservationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $observation = $resource->observation()->create($body['observation']);
            $this->createChildModels($observation, $body, ['identifier', 'note', 'referenceRange']);
            $this->createNestedInstances($observation, 'component', $body, ['referenceRange']);
            $this->createResourceContent(ObservationResource::class, $resource);
            return response()->json($observation, 201);
        });
    }


    public function update(ObservationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $observation = $resource->observation()->first();
            $observation->update($body['observation']);
            $this->updateChildModels($observation, $body, ['identifier', 'note', 'referenceRange']);
            $this->updateNestedInstances($observation, 'component', $body, ['referenceRange']);
            $this->createResourceContent(ObservationResource::class, $resource);
            return response()->json($observation, 200);
        });
    }
}
