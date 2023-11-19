<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompositionRequest;
use App\Http\Resources\CompositionResource;
use App\Services\FhirService;

class CompositionController extends Controller
{
    /**
     * Store a new composition.
     *
     * @param CompositionRequest $request The request object.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function store(CompositionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Composition');
            $composition = $resource->composition()->create($body['composition']);
            $this->createChildModels($composition, $body, ['category', 'author', 'attester', 'relatesTo']);
            $this->createNestedInstances($composition, 'event', $body, ['code', 'detail']);
            $this->createNestedInstances($composition, 'section', $body, ['author', 'entry']);
            $this->createResourceContent(CompositionResource::class, $resource);
            return response()->json($resource->composition->first(), 201);
        });
    }


    /**
     * Update a composition.
     *
     * @param CompositionRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(CompositionRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $composition = $resource->composition()->first();
            $composition->update($body['composition']);
            $compositionId = $composition->id;
            $this->updateChildModels($composition, $body, ['category', 'author', 'attester', 'relatesTo'], 'composition_id', $compositionId);
            $this->updateNestedInstances($composition, 'event', $body, 'composition_id', $compositionId, ['code', 'detail'], 'composition_event_id');
            $this->updateNestedInstances($composition, 'section', $body, 'composition_id', $compositionId, ['author', 'entry'], 'composition_section_id');
            $this->createResourceContent(CompositionResource::class, $resource);
            return response()->json($resource->composition->first(), 200);
        });
    }
}
