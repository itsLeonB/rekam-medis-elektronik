<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\MedicationRequestRequest;
use App\Http\Resources\MedicationRequestResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MedicationRequestController extends FhirController
{
    const RESOURCE_TYPE = 'MedicationRequest';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new MedicationRequestResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(MedicationRequestRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $medicationRequest = $resource->medicationRequest()->create($body['medicationRequest']);
            $this->createChildModels($medicationRequest, $body, ['identifier', 'note']);
            $this->createNestedInstances($medicationRequest, 'dosage', $body, ['doseRate']);
            $this->createResourceContent(MedicationRequestResource::class, $resource);
            return response()->json($medicationRequest, 201);
        });
    }


    public function update(MedicationRequestRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $medicationRequest = $resource->medicationRequest()->first();
            $medicationRequest->update($body['medicationRequest']);
            $this->updateChildModels($medicationRequest, $body, ['identifier', 'note']);
            $this->updateNestedInstances($medicationRequest, 'dosage', $body, ['doseRate']);
            $this->createResourceContent(MedicationRequestResource::class, $resource);
            return response()->json($medicationRequest, 200);
        });
    }
}
