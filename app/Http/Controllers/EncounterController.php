<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EncounterRequest;
use App\Http\Resources\EncounterResource;
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
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EncounterController extends Controller
{
    public function postEncounter(EncounterRequest $request)
    {
        $body = json_decode($request->getContent(), true);
        $body = removeEmptyValues($body);

        DB::beginTransaction();

        try {
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

            DB::commit();

            return response()->json($resource->encounter->first(), 201);
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
}
