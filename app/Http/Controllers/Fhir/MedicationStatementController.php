<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationStatementRequest;
use App\Http\Resources\MedicationStatementResource;
use App\Services\FhirService;

class MedicationStatementController extends Controller
{
    public function store(MedicationStatementRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        // return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('MedicationStatement');
            $statement = $resource->medicationStatement()->create($body['medicationStatement']);
            $this->createChildModels($statement, $body, ['note']);
            $this->createNestedInstances($statement, 'dosage', $body, ['doseRate']);
            $this->createResourceContent(MedicationStatementResource::class, $resource);
            return response()->json($resource->medicationStatement()->first(), 201);
        // });
    }


    public function update(MedicationStatementRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $statement = $resource->medicationStatement()->first();
            $statement->update($body['medicationStatement']);
            $statementId = $statement->id;
            $this->updateChildModels($statement, $body, ['note', 'dosage'], 'observation_id', $statementId);
            $this->updateNestedInstances($statement, 'dosage', $body, 'observation_id', $statementId, ['doseRate'], 'med_state_id');
            $this->createResourceContent(MedicationStatementResource::class, $resource);
            return response()->json($resource->medicationStatement()->first(), 200);
        });
    }
}
