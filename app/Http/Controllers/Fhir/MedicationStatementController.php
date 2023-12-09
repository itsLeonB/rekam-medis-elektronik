<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationStatementRequest;
use App\Http\Resources\MedicationStatementResource;
use App\Models\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MedicationStatementController extends Controller
{
    const RESOURCE_TYPE = 'MedicationStatement';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new MedicationStatementResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(MedicationStatementRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $statement = $resource->medicationStatement()->create($body['medicationStatement']);
            $this->createChildModels($statement, $body, ['identifier', 'reasonCode', 'note']);
            $this->createNestedInstances($statement, 'dosage', $body, ['doseRate']);
            $this->createResourceContent(MedicationStatementResource::class, $resource);
            return response()->json($statement, 201);
        });
    }


    public function update(MedicationStatementRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $statement = $resource->medicationStatement()->first();
            $statement->update($body['medicationStatement']);
            $this->updateChildModels($statement, $body, ['identifier', 'reasonCode', 'note']);
            $this->updateNestedInstances($statement, 'dosage', $body, ['doseRate']);
            $this->createResourceContent(MedicationStatementResource::class, $resource);
            return response()->json($statement, 200);
        });
    }
}
