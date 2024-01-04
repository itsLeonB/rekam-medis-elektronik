<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\PractitionerRequest;
use App\Http\Resources\PractitionerResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class PractitionerController extends FhirController
{
    const RESOURCE_TYPE = 'Practitioner';

    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new PractitionerResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(PractitionerRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id'] ?? null);
            $processor = new Processor();
            $data = $processor->generatePractitioner($body);
            $processor->savePractitioner($resource, $data);
            $this->createResourceContent(PractitionerResource::class, $resource);
            return response()->json(new PractitionerResource($resource), 201);
        });
    }
}
