<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConditionRequest;
use App\Http\Resources\ConditionResource;
use App\Services\FhirService;

class ConditionController extends Controller
{
    /**
     * Store a new condition.
     *
     * @param ConditionRequest $request The request object containing the condition data.
     * @param FhirService $fhirService The FHIR service used to insert the data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the newly created condition.
     */
    public function store(ConditionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Condition');
            $condition = $resource->condition()->create($body['condition']);
            $this->createChildModels($condition, $body, ['identifier', 'category', 'bodySite', 'evidence', 'note']);
            $this->createNestedInstances($condition, 'stage', $body, ['assessment']);
            $this->createResourceContent(ConditionResource::class, $resource);
            return response()->json($resource->condition->first(), 201);
        });
    }


    /**
     * Update a condition.
     *
     * @param ConditionRequest $request The request object.
     * @param int $res_id The resource ID.
     * @param FhirService $fhirService The FHIR service.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(ConditionRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $condition = $resource->condition()->first();
            $condition->update($body['condition']);
            $conditionId = $condition->id;
            $this->updateChildModels($condition, $body, ['identifier', 'category', 'bodySite', 'evidence', 'note'], 'condition_id', $conditionId);
            $this->updateNestedInstances($condition, 'stage', $body, 'condition_id', $conditionId, ['assessnebt'], 'condition_stage_id');
            $this->createResourceContent(ConditionResource::class, $resource);
            return response()->json($resource->condition->first(), 200);
        });
    }
}
