<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Controllers\SatusehatController;
use App\Http\Requests\Fhir\AllergyIntoleranceRequest;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AllergyIntoleranceController extends FhirController
{
    const RESOURCE_TYPE = 'AllergyIntolerance';

    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new AllergyIntoleranceResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Data tidak ditemukan.',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(AllergyIntoleranceRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateAllergyIntolerance($body);
            $processor->saveAllergyIntolerance($resource, $data);
            $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
            return response()->json(new AllergyIntoleranceResource($resource), 201);
        });
    }

    public function update(AllergyIntoleranceRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return AllergyIntolerance::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateAllergyIntolerance($resource, $body);
                $this->createResourceContent(AllergyIntoleranceResource::class, $resource);
                return response()->json(new AllergyIntoleranceResource($resource), 200);
            });
        });
    }
}
