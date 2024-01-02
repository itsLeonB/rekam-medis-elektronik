<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\MedicationStatementRequest;
use App\Http\Resources\MedicationStatementResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\MedicationStatement;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class MedicationStatementController extends FhirController
{
    const RESOURCE_TYPE = 'MedicationStatement';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new MedicationStatementResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateMedicationStatement($body);
            $processor->saveMedicationStatement($resource, $data);
            $this->createResourceContent(MedicationStatementResource::class, $resource);
            return response()->json(new MedicationStatementResource($resource), 201);
        });
    }

    public function update(MedicationStatementRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return MedicationStatement::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateMedicationStatement($resource, $body);
                $this->createResourceContent(MedicationStatementResource::class, $resource);
                return response()->json(new MedicationStatementResource($resource), 200);
            });
        });
    }
}
