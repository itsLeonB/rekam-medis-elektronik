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

    public function testResource()
    {
        $data = '{
            "condition": {
            "clinical_status": "active",
            "verification_status": "unconfirmed",
            "severity": "24484000",
            "code": "A00",
            "subject": "Patient/100000030009",
            "encounter": "Encounter/3dedcec9-885d-435e-9ac5-58853cb216bb",
            "onset": {
            "onsetDateTime": "2023-10-31T11:43:23+07:00",
            "onsetAge": {
            "value": null,
            "comparator": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "onsetPeriod": {
            "start": null,
            "end": null
            },
            "onsetRange": {
            "low": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "high": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            }
            },
            "onsetString": null
            },
            "abatement": {
            "abatementDateTime": "2023-10-31T11:43:23+07:00",
            "abatementAge": {
            "value": null,
            "comparator": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "abatementPeriod": {
            "start": null,
            "end": null
            },
            "abatementRange": {
            "low": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            },
            "high": {
            "value": null,
            "unit": null,
            "system": null,
            "code": null
            }
            },
            "abatementString": null
            },
            "recordedDate": "2023-11-01T11.16.01+07:00",
            "recorder": "Practitioner/1000400104",
            "asserter": "Practitioner-1000400104"
            },
            "identifier": [
            {
            "system": "http://sys-ids.kemkes.go.id/condition/10000004",
            "use": "official",
            "value": "5234342"
            }
            ],
            "category": [
            {
            "system": "http://terminology.hl7.org/CodeSystem/condition-category",
            "code": "encounter-diagnosis",
            "display": "Encounter Diagnosis"
            }
            ],
            "body_site": [
            {
            "system": "http://snomed.info/sct",
            "code": "111002",
            "display": "Parathyroid gland"
            }
            ],
            "stage": [
            {
            "stage_data": {
            "summary_system": null,
            "summary_code": null,
            "summary_display": null,
            "type_system": null,
            "type_code": null,
            "type_display": null
            },
            "assessment": [
            {
            "reference": null
            }
            ]
            }
            ],
            "evidence": [
            {
            "system": null,
            "code": null,
            "display": null,
            "detail_reference": null
            }
            ],
            "note": [
            {
            "author": {
            "authorString": "Dokter Bronsig",
            "authorReference": {
            "reference": "Practitioner/1000400104"
            }
            },
            "time": "2023-11-01T11:31:00+07:00",
            "text": "# Catatan<br>## Subbab"
            }
            ]
            }';

        $body = json_decode($data, true);
        $body = removeEmptyValues($body);

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

        return $resourceData;
    }
}
