<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\MedicationRequest;
use App\Http\Resources\MedicationResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Medication;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MedicationController extends FhirController
{
    const RESOURCE_TYPE = 'Medication';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new MedicationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(MedicationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id'] ?? null);
            $processor = new Processor();
            $data = $processor->generateMedication($body);
            $processor->saveMedication($resource, $data);
            $this->createResourceContent(MedicationResource::class, $resource);
            return response()->json(new MedicationResource($resource), 201);
        });
    }

    public function update(MedicationRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Medication::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateMedication($resource, $body);
                $this->createResourceContent(MedicationResource::class, $resource);
                return response()->json(new MedicationResource($resource), 200);
            });
        });
    }
}
