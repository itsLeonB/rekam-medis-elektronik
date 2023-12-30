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
use Throwable;

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

    public function update(ConditionRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            $resource = $this->updateResource($satusehat_id);
            $processor = new Processor();
            $processor->updateCondition($resource, $body);
            $this->createResourceContent(ConditionResource::class, $resource);
            return response()->json(new ConditionResource($resource), 200);
        });
    }
}
