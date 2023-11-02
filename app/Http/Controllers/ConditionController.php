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
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConditionController extends Controller
{
    public function postCondition(ConditionRequest $request)
    {
        $body = json_decode($request->getContent(), true);
        $body = removeEmptyValues($body);

        DB::beginTransaction();

        try {
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
        if (isset($body['stage'])) {
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

        DB::commit();

        return response()->json($resource->condition->first(), 201);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());
            return response()->json(['error' => 'Database error dalam input data pasien baru.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error dalam input data pasien baru.'], 500);
        }
    }
}
