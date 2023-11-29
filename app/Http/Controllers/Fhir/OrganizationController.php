<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Services\FhirService;

class OrganizationController extends Controller
{
    public function store(OrganizationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('Organization');
            $organization = $resource->organization()->create($body['organization']);
            $this->createChildModels($organization, $body, ['identifier', 'telecom', 'address']);
            $this->createNestedInstances($organization, 'contact', $body, ['telecom']);
            $this->createResourceContent(OrganizationResource::class, $resource);
            return response()->json($resource->organization()->first(), 201);
        });
    }


    public function update(OrganizationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $organization = $resource->organization()->first();
            $organization->update($body['organization']);
            $organizationId = $organization->id;
            $this->updateChildModels($organization, $body, ['identifier', 'telecom', 'address'], 'organization_id', $organizationId);
            $this->updateNestedInstances($organization, 'contact', $body, 'organization_id', $organizationId, ['telecom'], 'contact_id');
            $this->createResourceContent(OrganizationResource::class, $resource);
            return response()->json($resource->organization()->first(), 200);
        });
    }
}
