<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\ConditionRequest;
use App\Http\Resources\ConditionResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ConditionController extends FhirController
{
    const RESOURCE_TYPE = 'Condition';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new ConditionResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(ConditionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateCondition($body);
            $processor->saveCondition($resource, $data);
            $this->createResourceContent(ConditionResource::class, $resource);
            return response()->json(new ConditionResource($resource), 201);
        });
    }

    public function update(ConditionRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $condition = $resource->condition()->first();
            $condition->update($body['condition']);
            $this->updateChildModels($condition, $body, ['identifier', 'stage', 'evidence', 'note']);
            $this->createResourceContent(ConditionResource::class, $resource);
            return response()->json($condition, 200);
        });
    }
}
