<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class PatientController extends FhirController
{
    const RESOURCE_TYPE = 'Patient';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new PatientResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(PatientRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generatePatient($body);
            $processor->savePatient($resource, $data);
            $this->createResourceContent(PatientResource::class, $resource);
            return response()->json(new PatientResource($resource), 201);
        });
    }
}
