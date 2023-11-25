<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImagingStudyRequest;
use App\Http\Resources\ImagingStudyResource;
use App\Services\FhirService;

class ImagingStudyController extends Controller
{
    public function store(ImagingStudyRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('ImagingStudy');
            $imagingStudy = $resource->imagingStudy()->create($body['imagingStudy']);
            $this->createChildModels($imagingStudy, $body, ['identifier', 'reasonCode', 'note']);
            $this->createNestedInstances($imagingStudy, 'series', $body, ['instance']);
            $this->createResourceContent(ImagingStudyResource::class, $resource);
            return response()->json($resource->imagingStudy()->first(), 201);
        });
    }


    public function update(ImagingStudyRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $imagingStudy = $resource->imagingStudy()->first();
            $imagingStudy->update($body['imagingStudy']);
            $imagingStudyId = $imagingStudy->id;
            $this->updateChildModels($imagingStudy, $body, ['identifier', 'reasonCode', 'note'], 'imaging_id', $imagingStudyId);
            $this->updateNestedInstances($imagingStudy, 'series', $body, 'imaging_id', $imagingStudyId, ['instance'], 'img_series_id');
            $this->createResourceContent(ImagingStudyResource::class, $resource);
            return response()->json($resource->imagingStudy()->first(), 200);
        });
    }
}
