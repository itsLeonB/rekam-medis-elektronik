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

            if (isset($body['identifier'])) {
                foreach ($body['identifier'] as $i) {
                    EncounterIdentifier::create(array_merge($encounterKey, $i));
                }
            }

            if (isset($body['status_history'])) {
                foreach ($body['status_history'] as $sh) {
                    EncounterStatusHistory::create(array_merge($encounterKey, $sh));
                }
            }

            if (isset($body['class_history'])) {
                foreach ($body['class_history'] as $ch) {
                    EncounterClassHistory::create(array_merge($encounterKey, $ch));
                }
            }

            if (isset($body['participant'])) {
                foreach ($body['participant'] as $p) {
                    EncounterParticipant::create(array_merge($encounterKey, $p));
                }
            }

            if (isset($body['reason'])) {
                foreach ($body['reason'] as $r) {
                    EncounterReason::create(array_merge($encounterKey, $r));
                }
            }

            if (isset($body['diagnosis'])) {
                foreach ($body['diagnosis'] as $d) {
                    EncounterDiagnosis::create(array_merge($encounterKey, $d));
                }
            }

            if (isset($body['hospitalization'])) {
                foreach ($body['hospitalization'] as $h) {
                    $hospitalization = EncounterHospitalization::create(array_merge($encounterKey, $h['hospitalization_data']));
                    $hospitalKey = ['enc_hosp_id' => $hospitalization->id];

                    if (is_array($h['diet']) || is_object($h['diet'])) {
                        foreach ($h['diet'] as $hd) {
                            EncounterHospitalizationDiet::create(array_merge($hospitalKey, $hd));
                        }
                    }

                    if (is_array($h['special_arrangement']) || is_object($h['special_arrangement'])) {
                        foreach ($h['special_arrangement'] as $hsa) {
                            EncounterHospitalizationSpecialArrangement::create(array_merge($hospitalKey, $hsa));
                        }
                    }
                }
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
            ],
            "diagnosis": [
            {
            "condition_reference": null,
            "condition_display": null,
            "use": null,
            "rank": null
            }
            ],
            "hospitalization": [
            {
            "hospitalization_data": {
            "preadmission_identifier_system": null,
            "preadmission_identifier_use": null,
            "preadmission_identifier_value": null,
            "origin": null,
            "admit_source": null,
            "re_admission": null,
            "destination": null,
            "discharge_disposition": null
            },
            "diet": [
            {
            "system": null,
            "code": null,
            "display": null
            }
            ],
            "special_arrangement": [
            {
            "system": null,
            "code": null,
            "display": null
            }
            ]
            }
            ]
            }';


        $body = json_decode($data, true);
        $body = removeEmptyValues($body);

        DB::beginTransaction();

        // try {
            $resource = Resource::create([
                'res_type' => 'Encounter',
                'res_ver' => 1,
            ]);

            $resourceKey = ['resource_id' => $resource->id];

            $encounter = Encounter::create(array_merge($resourceKey, $body['encounter']));

            $encounterKey = ['encounter_id' => $encounter->id];

            if (isset($body['identifier'])) {
                foreach ($body['identifier'] as $i) {
                    EncounterIdentifier::create(array_merge($encounterKey, $i));
                }
            }

            if (isset($body['status_history'])) {
                foreach ($body['status_history'] as $sh) {
                    EncounterStatusHistory::create(array_merge($encounterKey, $sh));
                }
            }

            if (isset($body['class_history'])) {
                foreach ($body['class_history'] as $ch) {
                    EncounterClassHistory::create(array_merge($encounterKey, $ch));
                }
            }

            if (isset($body['participant'])) {
                foreach ($body['participant'] as $p) {
                    EncounterParticipant::create(array_merge($encounterKey, $p));
                }
            }

            if (isset($body['reason'])) {
                foreach ($body['reason'] as $r) {
                    EncounterReason::create(array_merge($encounterKey, $r));
                }
            }

            if (isset($body['diagnosis'])) {
                foreach ($body['diagnosis'] as $d) {
                    EncounterDiagnosis::create(array_merge($encounterKey, $d));
                }
            }

            if (isset($body['hospitalization'])) {
                foreach ($body['hospitalization'] as $h) {
                    $hospitalization = EncounterHospitalization::create(array_merge($encounterKey, $h['hospitalization_data']));
                    $hospitalKey = ['enc_hosp_id' => $hospitalization->id];

                    if (is_array($h['diet']) || is_object($h['diet'])) {
                        foreach ($h['diet'] as $hd) {
                            EncounterHospitalizationDiet::create(array_merge($hospitalKey, $hd));
                        }
                    }

                    if (is_array($h['special_arrangement']) || is_object($h['special_arrangement'])) {
                        foreach ($h['special_arrangement'] as $hsa) {
                            EncounterHospitalizationSpecialArrangement::create(array_merge($hospitalKey, $hsa));
                        }
                    }
                }
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
        // } catch (QueryException $e) {
        //     DB::rollBack();
        //     Log::error('Database error: ' . $e->getMessage());
        //     return response()->json(['error' => 'Database error dalam input data pasien baru.'], 500);
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     Log::error('Error: ' . $e->getMessage());
        //     return response()->json(['error' => 'Server error dalam input data pasien baru.'], 500);
        // }
    }
}
