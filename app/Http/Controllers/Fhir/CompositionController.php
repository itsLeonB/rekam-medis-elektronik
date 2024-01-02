<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\CompositionRequest;
use App\Http\Resources\CompositionResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Composition;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class CompositionController extends FhirController
{
    const RESOURCE_TYPE = 'Composition';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new CompositionResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateComposition($body);
            $processor->saveComposition($resource, $data);
            $this->createResourceContent(CompositionResource::class, $resource);
            return response()->json(new CompositionResource($resource), 201);
        });
    }

    public function update(CompositionRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Composition::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateComposition($resource, $body);
                $this->createResourceContent(CompositionResource::class, $resource);
                return response()->json(new CompositionResource($resource), 200);
            });
        });
    }
}
