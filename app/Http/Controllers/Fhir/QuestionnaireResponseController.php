<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fhir\QuestionnaireResponseRequest;
use App\Http\Resources\QuestionnaireResponseResource;
use App\Models\Fhir\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class QuestionnaireResponseController extends Controller
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
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $questionnaireResponse = $resource->questionnaireResponse()->create($body['questionnaireResponse']);
            $this->createNestedInstances($questionnaireResponse, 'item', $body, ['answer']);
            $this->createResourceContent(QuestionnaireResponseResource::class, $resource);
            return response()->json($questionnaireResponse, 201);
        });
    }


    public function update(QuestionnaireResponseRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $questionnaireResponse = $resource->questionnaireResponse()->first();
            $questionnaireResponse->update($body['questionnaireResponse']);
            $this->updateNestedInstances($questionnaireResponse, 'item', $body, ['answer']);
            $this->createResourceContent(QuestionnaireResponseResource::class, $resource);
            return response()->json($questionnaireResponse, 200);
        });
    }
}
