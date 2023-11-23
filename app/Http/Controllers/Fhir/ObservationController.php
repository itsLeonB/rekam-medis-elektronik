<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\ObservationRequest;
use App\Http\Resources\ObservationResource;
use App\Services\FhirService;

class ObservationController extends Controller
{
    /**
     * Store a new observation.
     *
     * @param ObservationRequest $request The observation request object.
     * @param FhirService $fhirService The FHIR service object.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the newly created observation.
     */
    public function store(ObservationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Observation');
            $observation = $resource->observation()->create($body['observation']);
            $this->createChildModels($observation, $body, ['identifier', 'basedOn', 'partOf', 'category', 'focus', 'performer', 'interpretation', 'note', 'referenceRange', 'member', 'derivedFrom']);
            $this->createNestedInstances($observation, 'component', $body, ['interpretation', 'referenceRange']);
            $this->createResourceContent(ObservationResource::class, $resource);
            return response()->json($resource->observation->first(), 201);
        });
    }


    /**
     * Update an observation.
     *
     * @param ObservationRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(ObservationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $observation = $resource->observation()->first();
            $observation->update($body['observation']);
            $observationId = $observation->id;
            $this->updateChildModels($observation, $body, ['identifier', 'basedOn', 'partOf', 'category', 'focus', 'performer', 'interpretation', 'note', 'referenceRange', 'member', 'derivedFrom'], 'observation_id', $observationId);
            $this->updateNestedInstances($observation, 'component', $body, 'observation_id', $observationId, ['interpretation', 'referenceRange'], 'obs_comp_id');
            $this->createResourceContent(ObservationResource::class, $resource);
            return response()->json($resource->observation->first(), 200);
        });
    }
}
