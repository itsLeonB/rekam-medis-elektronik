<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\ProcedureRequest;
use App\Http\Resources\ProcedureResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Procedure;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcedureController extends FhirController
{
    const RESOURCE_TYPE = 'Procedure';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new ProcedureResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(ProcedureRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateProcedure($body);
            $processor->saveProcedure($resource, $data);
            $this->createResourceContent(ProcedureResource::class, $resource);
            return response()->json(new ProcedureResource($resource), 201);
        });
    }

    public function update(ProcedureRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Procedure::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateProcedure($resource, $body);
                $this->createResourceContent(ProcedureResource::class, $resource);
                return response()->json(new ProcedureResource($resource), 200);
            });
        });
    }
}
