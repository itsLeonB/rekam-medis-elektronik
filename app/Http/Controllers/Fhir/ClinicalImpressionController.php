<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\ClinicalImpressionRequest;
use App\Http\Resources\ClinicalImpressionResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ClinicalImpressionController extends FhirController
{
    const RESOURCE_TYPE = 'ClinicalImpression';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new ClinicalImpressionResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(ClinicalImpressionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateClinicalImpression($body);
            $processor->saveClinicalImpression($resource, $data);
            $this->createResourceContent(ClinicalImpressionResource::class, $resource);
            return response()->json(new ClinicalImpressionResource($resource), 201);
        });
    }

    public function update(ClinicalImpressionRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $clinicalImpression = $resource->clinicalImpression()->first();
            $clinicalImpression->update($body['clinicalImpression']);
            $this->updateChildModels($clinicalImpression, $body, ['identifier', 'investigation', 'finding', 'note']);
            $this->createResourceContent(ClinicalImpressionResource::class, $resource);
            return response()->json($clinicalImpression, 200);
        });
    }
}
