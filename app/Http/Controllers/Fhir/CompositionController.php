<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\CompositionRequest;
use App\Http\Resources\CompositionResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class CompositionController extends Controller
{
    const RESOURCE_TYPE = 'Composition';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new CompositionResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(CompositionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $composition = $resource->composition()->create($body['composition']);
            $this->createChildModels($composition, $body, ['attester', 'relatesTo', 'event', 'section']);
            $this->createResourceContent(CompositionResource::class, $resource);
            return response()->json($composition, 201);
        });
    }


    public function update(CompositionRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $composition = $resource->composition()->first();
            $composition->update($body['composition']);
            $this->updateChildModels($composition, $body, ['attester', 'relatesTo', 'event', 'section']);
            $this->createResourceContent(CompositionResource::class, $resource);
            return response()->json($composition, 200);
        });
    }
}
