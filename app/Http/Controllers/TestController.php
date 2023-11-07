<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllergyIntoleranceResource;
use App\Http\Resources\ConditionResource;
use App\Http\Resources\EncounterResource;
use App\Http\Resources\MedicationResource;
use App\Http\Resources\ObservationResource;
use App\Http\Resources\ProcedureResource;
use App\Models\Condition;
use App\Models\ConditionBodySite;
use App\Models\ConditionCategory;
use App\Models\ConditionEvidence;
use App\Models\ConditionIdentifier;
use App\Models\ConditionNote;
use App\Models\ConditionStage;
use App\Models\ConditionStageAssessment;
use App\Models\Encounter;
use App\Models\EncounterClassHistory;
use App\Models\EncounterDiagnosis;
use App\Models\EncounterHospitalization;
use App\Models\EncounterHospitalizationDiet;
use App\Models\EncounterHospitalizationSpecialArrangement;
use App\Models\EncounterIdentifier;
use App\Models\EncounterParticipant;
use App\Models\EncounterReason;
use App\Models\EncounterStatusHistory;
use App\Models\Resource;
use App\Models\ResourceContent;
use Illuminate\Support\Facades\DB;
use Tests\Traits\ExamplePayload;

class TestController extends Controller
{
    use ExamplePayload;

    public function testMedicationResource($satusehat_id)
    {
        return response()->json(new MedicationResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'medication']
        ])->firstOrFail()), 200);
    }

    public function testProcedureResource($satusehat_id)
    {
        return response()->json(new ProcedureResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'procedure']
        ])->firstOrFail()), 200);
    }

    public function testObservationResource($satusehat_id)
    {
        return response()->json(new ObservationResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'observation']
        ])->firstOrFail()), 200);
    }

    public function testAllergyIntoleranceResource($satusehat_id)
    {
        return response()->json(new AllergyIntoleranceResource(Resource::where([
            ['satusehat_id', '=', $satusehat_id],
            ['res_type', '=', 'allergyintolerance']
        ])->firstOrFail()), 200);
    }

    public function testCreateEncounter()
    {
        $data = '{
            "encounter": {
            "status": "arrived",
            "class": "AMB",
            "service_type": 117,
            "priority": "A",
            "subject": "Patient/100000030009",
            "episode_of_care": null,
            "based_on": null,
            "period_start": "2023-10-31T10:49:00+07:00",
            "period_end": null,
            "account": null,
            "location": "Location/dc01c797-547a-4e4d-97cd-4ece0630e380",
            "service_provider": "Organization/RSPARA",
            "part_of": null
            },
            "identifier": [
            {
            "system": "http://sys-ids.kemkes.go.id/encounter/RSPARA",
            "use": "official",
            "value": "000001"
            }
            ],
            "status_history": [
            {
            "status": "arrived",
            "period_start": "2023-10-31T10:49:00+07:00",
            "period_end": null
            }
            ],
            "class_history" : [
            {
            "class": "AMB",
            "period_start": "2023-10-31T10:49:00+07:00",
            "period_end": null
            }
            ],
            "participant": [
            {
            "type": "ATND",
            "individual": "Practitioner/1000400104"
            }
            ],
            "reason": [
            {
            "code": 160303001,
            "reference": "Condition/ba0dd351-c30a-4659-994e-0013797b545b"
            }
            ]
            }';
        $body = json_decode($data, true);
        $body = removeEmptyValues($body);

        $resource = Resource::create([
            'res_type' => 'Encounter',
            'res_ver' => 1,
        ]);

        $resourceKey = ['resource_id' => $resource->id];

        $encounter = Encounter::create(array_merge($resourceKey, $body['encounter']));

        $encounterKey = ['encounter_id' => $encounter->id];

        $this->createInstances(EncounterIdentifier::class, $encounterKey, $body, 'identifier');
        $this->createInstances(EncounterStatusHistory::class, $encounterKey, $body, 'status_history');
        $this->createInstances(EncounterClassHistory::class, $encounterKey, $body, 'class_history');
        $this->createInstances(EncounterParticipant::class, $encounterKey, $body, 'participant');
        $this->createInstances(EncounterReason::class, $encounterKey, $body, 'reason');
        $this->createInstances(EncounterDiagnosis::class, $encounterKey, $body, 'diagnosis');

        if (isset($body['hospitalization'])) {
            $this->createInstances(EncounterHospitalization::class, $encounterKey, $body['hospitalization'], 'hospitalization_data', [
                [
                    'model' => EncounterHospitalizationDiet::class,
                    'key' => 'enc_hosp_id',
                    'bodyKey' => 'diet'
                ],
                [
                    'model' => EncounterHospitalizationSpecialArrangement::class,
                    'key' => 'enc_hosp_id',
                    'bodyKey' => 'special_arrangement'
                ],
            ]);
        }

        $resourceData = new EncounterResource($resource);
        $resourceText = json_encode($resourceData);

        ResourceContent::create([
            'resource_id' => $resource->id,
            'res_ver' => 1,
            'res_text' => $resourceText,
        ]);

        return response()->json($resource->encounter->first(), 201);
    }

    public function testCreateCondition()
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
