<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\MedicationDispenseRequest;
use App\Http\Resources\MedicationDispenseResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MedicationDispenseController extends Controller
{
    const RESOURCE_TYPE = 'MedicationDispense';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new MedicationDispenseResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(MedicationDispenseRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $medicationDispense = $resource->medicationDispense()->create($body['medicationDispense']);
            $this->createChildModels($medicationDispense, $body, ['identifier', 'performer']);
            $this->createNestedInstances($medicationDispense, 'dosageInstruction', $body, ['doseRate']);
            $this->createResourceContent(MedicationDispenseResource::class, $resource);
            return response()->json($medicationDispense, 201);
        });
    }


    public function update(MedicationDispenseRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $medicationDispense = $resource->medicationDispense()->first();
            $medicationDispense->update($body['medicationDispense']);
            $this->updateChildModels($medicationDispense, $body, ['identifier', 'performer']);
            $this->updateNestedInstances($medicationDispense, 'dosageInstruction', $body, ['doseRate']);
            $this->createResourceContent(MedicationDispenseResource::class, $resource);
            return response()->json($medicationDispense, 200);
        });
    }
}
