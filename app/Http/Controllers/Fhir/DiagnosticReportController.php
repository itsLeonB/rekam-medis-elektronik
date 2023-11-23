<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiagnosticReportRequest;
use App\Http\Resources\DiagnosticReportResource;
use App\Services\FhirService;

class DiagnosticReportController extends Controller
{
    public function store(DiagnosticReportRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('DiagnosticReport');
            $diagnostic = $resource->diagnostic()->create($body['diagnostic']);
            $this->createChildModels($diagnostic, $body, ['media', 'conclusionCode']);
            $this->createResourceContent(DiagnosticReportResource::class, $resource);
            return response()->json($resource->diagnostic()->first(), 201);
        });
    }


    public function update(DiagnosticReportRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $diagnostic = $resource->diagnostic()->first();
            $diagnostic->update($body['diagnostic']);
            $diagnosticId = $diagnostic->id;
            $this->updateChildModels($diagnostic, $body, ['media', 'conclusionCode'], 'diagnostic_id', $diagnosticId);
            $this->createResourceContent(DiagnosticReportResource::class, $resource);
            return response()->json($resource->diagnostic()->first(), 200);
        });
    }
}
