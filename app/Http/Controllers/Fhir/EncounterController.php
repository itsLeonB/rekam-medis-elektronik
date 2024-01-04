<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\EncounterRequest;
use App\Http\Resources\EncounterResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class EncounterController extends FhirController
{
    const RESOURCE_TYPE = 'Encounter';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new EncounterResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(EncounterRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id'] ?? null);
            $processor = new Processor();
            $data = $processor->generateEncounter($body);
            $processor->saveEncounter($resource, $data);
            $this->createResourceContent(EncounterResource::class, $resource);
            return response()->json(new EncounterResource($resource), 201);
        });
    }

    public function update(EncounterRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Encounter::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateEncounter($resource, $body);
                $this->createResourceContent(EncounterResource::class, $resource);
                return response()->json(new EncounterResource($resource), 200);
            });
        });
    }
}
