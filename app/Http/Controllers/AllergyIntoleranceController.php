<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllergyIntoleranceRequest;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Models\Resource;
use App\Services\FhirService;

class AllergyIntoleranceController extends Controller
{
    /**
     * Store a newly created AllergyIntolerance resource in storage.
     *
     * @param  \App\Http\Requests\AllergyIntoleranceRequest  $request
     * @param  \App\Services\FhirService  $fhirService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('AllergyIntolerance');
            $allergyIntolerance = $resource->allergyIntolerance()->create($body['allergy_intolerance']);
            $this->createInstances($allergyIntolerance, 'identifier', $body);
            $this->createInstances($allergyIntolerance, 'note', $body);
            $this->createNestedInstances($allergyIntolerance, 'reaction', $body, ['manifestation', 'note']);
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json($resource->allergyIntolerance->first(), 201);
        });
    }

    /**
     * Update an existing AllergyIntolerance resource.
     *
     * @param AllergyIntoleranceRequest $request The HTTP request instance.
     * @param FhirService $fhirService The FHIR service instance.
     * @return \Illuminate\Http\JsonResponse The HTTP response instance.
     */
    public function update(AllergyIntoleranceRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);

            $allergyIntolerance = $resource->allergyIntolerance()->first();
            $allergyIntolerance->update($body['allergy_intolerance']);
            $allergyId = $allergyIntolerance->id;

            $this->updateInstances($allergyIntolerance, 'identifier', $body, 'allergy_id', $allergyId);
            $this->updateInstances($allergyIntolerance, 'note', $body, 'allergy_id', $allergyId);
            $this->updateNestedInstances($allergyIntolerance, 'reaction', $body, 'allergy_id', $allergyId, ['manifestation', 'note'], 'allergy_react_id');

            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);

            return response()->json($resource->allergyIntolerance->first(), 200);
        });
    }
}
