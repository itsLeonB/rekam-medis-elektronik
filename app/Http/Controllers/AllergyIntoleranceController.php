<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AllergyIntoleranceRequest;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Services\FhirService;

class AllergyIntoleranceController extends Controller
{
    /**
     * Store a new AllergyIntolerance resource.
     *
     * @param AllergyIntoleranceRequest $request The request object.
     * @param FhirService $fhirService The FhirService instance.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the created AllergyIntolerance resource.
     */
    public function store(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        // return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('AllergyIntolerance');
            $allergyIntolerance = $resource->allergyIntolerance()->create($body['allergyIntolerance']);
            $this->createChildModels($allergyIntolerance, $body, ['identifier', 'note']);
            $this->createNestedInstances($allergyIntolerance, 'reaction', $body, ['manifestation', 'note']);
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json($resource->allergyIntolerance->first(), 201);
        // });
    }


    /**
     * Update an AllergyIntolerance resource.
     *
     * @param AllergyIntoleranceRequest $request The request object containing the data.
     * @param int $res_id The ID of the resource to be updated.
     * @param FhirService $fhirService The FhirService instance for inserting data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the updated AllergyIntolerance resource.
     */
    public function update(AllergyIntoleranceRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $allergyIntolerance = $resource->allergyIntolerance()->first();
            $allergyIntolerance->update($body['allergyIntolerance']);
            $allergyId = $allergyIntolerance->id;
            $this->updateChildModels($allergyIntolerance, $body, ['identifier', 'note'], 'allergy_id', $allergyId);
            $this->updateNestedInstances($allergyIntolerance, 'reaction', $body, 'allergy_id', $allergyId, ['manifestation', 'note'], 'allergy_react_id');
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json($resource->allergyIntolerance->first(), 200);
        });
    }
}
