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
use App\Services\FhirService;

class EncounterController extends Controller
{
    public function postEncounter(EncounterRequest $request, FhirService $fhirService)
    {
        $body = $this->retrieveJsonPayload($request);

        return $fhirService->insertData(function () use ($body) {
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

            if (isset($body['hospitalization']) && !empty($body['hospitalization'])) {
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
        });
    }
}
