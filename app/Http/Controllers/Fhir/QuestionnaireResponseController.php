<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionnaireResponseRequest;
use App\Http\Resources\QuestionnaireResponseResource;
use App\Services\FhirService;

class QuestionnaireResponseController extends Controller
{
    public function store(QuestionnaireResponseRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('QuestionnaireResponse');
            $questionnaireResponse = $resource->questionnaireResponse()->create($body['questionnaireResponse']);
            $this->createChildModels($questionnaireResponse, $body, ['item']);
            $this->createResourceContent(QuestionnaireResponseResource::class, $resource);
            return response()->json($resource->questionnaireResponse()->first(), 201);
        });
    }


    public function update(QuestionnaireResponseRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $questionnaireResponse = $resource->questionnaireResponse()->first();
            $questionnaireResponse->update($body['questionnaireResponse']);
            $questionnaireResponseId = $questionnaireResponse->id;
            $this->updateChildModels($questionnaireResponse, $body, ['item'], 'questionnaire_id', $questionnaireResponseId);
            $this->createResourceContent(QuestionnaireResponseResource::class, $resource);
            return response()->json($resource->questionnaireResponse()->first(), 200);
        });
    }
}
