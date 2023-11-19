<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicalImpressionRequest;
use App\Http\Resources\ClinicalImpressionResource;
use App\Services\FhirService;

class ClinicalImpressionController extends Controller
{
    /**
     * Store a new ClinicalImpression resource.
     *
     * @param ClinicalImpressionRequest $request The request object.
     * @param FhirService $fhirService The FhirService instance.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the created ClinicalImpression resource.
     */
    public function store(ClinicalImpressionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            $resource = $this->createResource('ClinicalImpression');
            $clinicalImpression = $resource->clinicalImpression()->create($body['clinical_impression']);
            $this->createChildModels($clinicalImpression, $body, ['identifier', 'problem', 'protocol', 'finding', 'prognosis', 'supportingInfo', 'note']);
            $this->createNestedInstances($clinicalImpression, 'investigation', $body, ['item']);
            $this->createResourceContent(ClinicalImpressionResource::class, $resource);
            return response()->json($resource->clinicalImpression->first(), 201);
        });
    }

    public function update(ClinicalImpressionRequest $request, int $res_id, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);
        return $fhirService->insertData(function () use ($body, $res_id) {
            $resource = $this->updateResource($res_id);

            $clinicalImpression = $resource->clinicalImpression()->first();
            $clinicalImpression->update($body['clinical_impression']);
            $impressionId = $clinicalImpression->id;

            $this->updateChildModels($clinicalImpression, $body, ['identifier', 'problem', 'protocol', 'finding', 'prognosis', 'supportingInfo', 'note'], 'impression_id', $impressionId);
            $this->updateNestedInstances($clinicalImpression, 'investigation', $body, 'impression_id', $impressionId, ['item'], 'impress_investigate_id');

            $this->createResourceContent(ClinicalImpressionResource::class, $resource);

            return response()->json($resource->clinicalImpression->first(), 200);
        });
    }
}
