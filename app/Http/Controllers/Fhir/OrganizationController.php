<?php

namespace App\Http\Controllers\Fhir;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Resource;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    const RESOURCE_TYPE = 'Organization';


    public function show($res_id)
    {
        try {
            return response()
                ->json(new OrganizationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['id', $res_id]
                ])->firstOrFail()), 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Model error: ' . $e->getMessage());
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
    }


    public function store(OrganizationRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource(self::RESOURCE_TYPE);
            $organization = $resource->organization()->create($body['organization']);
            $this->createChildModels($organization, $body, ['identifier', 'telecom', 'address']);
            $this->createNestedInstances($organization, 'contact', $body, ['telecom']);
            $this->createResourceContent(OrganizationResource::class, $resource);
            return response()->json($organization, 201);
        });
    }


    public function update(OrganizationRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);
            $organization = $resource->organization()->first();
            $organization->update($body['organization']);
            $this->updateChildModels($organization, $body, ['identifier', 'telecom', 'address']);
            $this->updateNestedInstances($organization, 'contact', $body, ['telecom']);
            $this->createResourceContent(OrganizationResource::class, $resource);
            return response()->json($organization, 200);
        });
    }
}
