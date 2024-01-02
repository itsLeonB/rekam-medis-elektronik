<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\MedicationRequestRequest;
use App\Http\Resources\MedicationRequestResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MedicationRequestController extends FhirController
{
    const RESOURCE_TYPE = 'MedicationRequest';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new MedicationRequestResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateMedicationRequest($body);
            $processor->saveMedicationRequest($resource, $data);
            $this->createResourceContent(MedicationRequestResource::class, $resource);
            return response()->json(new MedicationRequestResource($resource), 201);
        });
    }

    public function update(MedicationRequestRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return MedicationRequest::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateMedicationRequest($resource, $body);
                $this->createResourceContent(MedicationRequestResource::class, $resource);
                return response()->json(new MedicationRequestResource($resource), 200);
            });
        });
    }
}
