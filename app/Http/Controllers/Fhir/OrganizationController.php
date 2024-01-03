<?php

namespace App\Http\Controllers\Fhir;

use App\Fhir\Processor;
use App\Http\Controllers\FhirController;
use App\Http\Requests\Fhir\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Organization;
use App\Services\FhirService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends FhirController
{
    const RESOURCE_TYPE = 'Organization';


    public function show($satusehat_id)
    {
        try {
            return response()
                ->json(new OrganizationResource(Resource::where([
                    ['res_type', self::RESOURCE_TYPE],
                    ['satusehat_id', $satusehat_id]
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
            $resource = $this->createResource(self::RESOURCE_TYPE, $body['id'] ?? null);
            $processor = new Processor();
            $orgData = $processor->generateOrganization($body);
            $processor->saveOrganization($resource, $orgData);
            $this->createResourceContent(OrganizationResource::class, $resource);
            return response()->json(new OrganizationResource($resource), 201);
        });
    }


    public function update(OrganizationRequest $request, string $satusehat_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $satusehat_id) {
            return Organization::withoutEvents(function () use ($body, $satusehat_id) {
                $resource = $this->updateResource($satusehat_id);
                $processor = new Processor();
                $processor->updateOrganization($resource, $body);
                $this->createResourceContent(OrganizationResource::class, $resource);
                return response()->json(new OrganizationResource($resource), 200);
            });
        });
    }
}
