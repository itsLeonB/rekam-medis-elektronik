<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConditionRequest;
use App\Http\Resources\ConditionResource;
use App\Models\Condition;
use App\Models\ConditionBodySite;
use App\Models\ConditionCategory;
use App\Models\ConditionEvidence;
use App\Models\ConditionIdentifier;
use App\Models\ConditionNote;
use App\Models\ConditionStage;
use App\Models\ConditionStageAssessment;
use App\Models\Resource;
use App\Models\ResourceContent;
use App\Services\FhirService;

class ConditionController extends Controller
{
    public function postCondition(ConditionRequest $request, FhirService $fhirService)
    {
        $body = json_decode($request->getContent(), true);
        $body = removeEmptyValues($body);

        return $fhirService->insertData(function () use ($body) {
            $resource = Resource::create([
                'res_type' => 'Condition',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $condition = Condition::create(array_merge($resourceKey, $body['condition']));

            $conditionKey = ['condition_id' => $condition->id];

            $this->createInstances(ConditionIdentifier::class, $conditionKey, $body, 'identifier');
            $this->createInstances(ConditionCategory::class, $conditionKey, $body, 'category');
            $this->createInstances(ConditionBodySite::class, $conditionKey, $body, 'body_site');
            if (isset($body['stage']) && !empty($body['stage'])) {
                $this->createInstances(ConditionStage::class, $conditionKey, $body['stage'], 'stage_data', [
                    [
                        'model' => ConditionStageAssessment::class,
                        'key' => 'condition_stage_id',
                        'bodyKey' => 'assessment'
                    ],
                ]);
            }

            $this->createInstances(ConditionEvidence::class, $conditionKey, $body, 'evidence');
            $this->createInstances(ConditionNote::class, $conditionKey, $body, 'note');

            $resourceData = new ConditionResource($resource);
            $resourceText = json_encode($resourceData);

            ResourceContent::create([
                'resource_id' => $resource->id,
                'res_ver' => 1,
                'res_text' => $resourceText,
            ]);

            return response()->json($resource->condition->first(), 201);
        });
    }
}
