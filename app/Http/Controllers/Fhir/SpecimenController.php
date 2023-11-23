<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecimenRequest;
use App\Http\Resources\SpecimenResource;
use App\Services\FhirService;

class SpecimenController extends Controller
{
    public function store(SpecimenRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Specimen');
            $specimen = $resource->specimen()->create($body['specimen']);
            $this->createChildModels($specimen, $body, ['identifier', 'parent', 'request', 'processing', 'condition', 'note']);
            $this->createNestedInstances($specimen, 'container', $body, ['identifier']);
            $this->createResourceContent(SpecimenResource::class, $resource);
            return response()->json($resource->specimen()->first(), 201);
        });
    }


    public function update(SpecimenRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $specimen = $resource->specimen()->first();
            $specimen->update($body['specimen']);
            $specimenId = $specimen->id;
            $this->updateChildModels($specimen, $body, ['identifier', 'parent', 'request', 'processing', 'condition', 'note'], 'specimen_id', $specimenId);
            $this->updateNestedInstances($specimen, 'container', $body, 'specimen_id', $specimenId, ['identifier'], 'container_id');
            $this->createResourceContent(SpecimenResource::class, $resource);
            return response()->json($resource->specimen()->first(), 200);
        });
    }
}
