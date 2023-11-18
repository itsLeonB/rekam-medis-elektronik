<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicalImpressionRequest;
use App\Http\Resources\ClinicalImpressionResource;
use App\Models\ClinicalImpression;
use App\Models\ClinicalImpressionFinding;
use App\Models\ClinicalImpressionIdentifier;
use App\Models\ClinicalImpressionInvestigation;
use App\Models\ClinicalImpressionInvestigationItem;
use App\Models\ClinicalImpressionNote;
use App\Models\ClinicalImpressionProblem;
use App\Models\ClinicalImpressionPrognosis;
use App\Models\ClinicalImpressionProtocol;
use App\Models\ClinicalImpressionSupportingInfo;
use App\Models\Resource;
use App\Services\FhirService;

class ClinicalImpressionController extends Controller
{
    public function store(ClinicalImpressionRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
            $resource = Resource::create([
                'res_type' => 'ClinicalImpression',
                'res_ver' => 1
            ]);
            $clinicalImpression = $resource->clinicalImpression()->create($body['clinical_impression']);
            $clinicalImpression->identifier()->createMany($body['identifier']);
            $clinicalImpression->problem()->createMany($body['problem']);

            if (!empty($body['investigation'])) {
                foreach ($body['investigation'] as $i) {}
                $investigation = $clinicalImpression->investigation()->
                $this->createNestedInstances(ClinicalImpressionInvestigation::class, $impressionKey, $body, 'investigation', [
                    [
                        'model' => ClinicalImpressionInvestigationItem::class,
                        'key' => 'impress_investigate_id',
                        'bodyKey' => 'item'
                    ]
                ]);
            }

            $this->createInstances(ClinicalImpressionProtocol::class, $impressionKey, $body, 'protocol');
            $this->createInstances(ClinicalImpressionFinding::class, $impressionKey, $body, 'finding');
            $this->createInstances(ClinicalImpressionPrognosis::class, $impressionKey, $body, 'prognosis');
            $this->createInstances(ClinicalImpressionSupportingInfo::class, $impressionKey, $body, 'supporting_info');
            $this->createInstances(ClinicalImpressionNote::class, $impressionKey, $body, 'note');

            $this->createResourceContent(ClinicalImpressionResource::class, $resource);

            return response()->json($resource->clinicalImpression->first(), 201);
        });
    }
}
