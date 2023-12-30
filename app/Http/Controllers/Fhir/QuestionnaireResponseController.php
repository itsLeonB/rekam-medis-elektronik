<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\QuestionnaireResponseRequest;
use App\Http\Resources\QuestionnaireResponseResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class QuestionnaireResponseController extends FhirController
{
    const RESOURCE_TYPE = 'QuestionnaireResponse';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new QuestionnaireResponseResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }

    public function store(QuestionnaireResponseRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id']);
            $processor = new Processor();
            $data = $processor->generateQuestionnaireResponse($body);
            $processor->saveQuestionnaireResponse($resource, $data);
            $this->createResourceContent(QuestionnaireResponseResource::class, $resource);
            return response()->json(new QuestionnaireResponseResource($resource), 201);
        });
    }

    public function update(QuestionnaireResponseRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            $resource = $this->updateResource($satusehat_id);
            $processor = new Processor();
            $processor->updateQuestionnaireResponse($resource, $body);
            $this->createResourceContent(QuestionnaireResponseResource::class, $resource);
            return response()->json(new QuestionnaireResponseResource($resource), 200);
        });
    }
}
