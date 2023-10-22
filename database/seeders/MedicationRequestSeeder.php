<?php

namespace Database\Seeders;

use App\Models\MedicationRequest;
use App\Models\MedicationRequestBasedOn;
use App\Models\MedicationRequestCategory;
use App\Models\MedicationRequestDosage;
use App\Models\MedicationRequestDosageAdditionalInstruction;
use App\Models\MedicationRequestDosageDoseRate;
use App\Models\MedicationRequestIdentifier;
use App\Models\MedicationRequestInsurance;
use App\Models\MedicationRequestNote;
use App\Models\MedicationRequestReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class MedicationRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicationRequests = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'MedicationRequest')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($medicationRequests as $mr) {
            $resContent = json_decode($mr->res_text, true);
            $dispenseInterval = returnAttribute($resContent, ['dispenseRequest', 'dispenseInterval'], null);
            $dispenseIntervalData = returnDuration($dispenseInterval, 'dispense_interval');
            $validity = returnAttribute($resContent, ['dispenseRequest', 'validityPeriod'], null);
            $validityPeriod = returnPeriod($validity, 'validity');
            $dispenseQuantity = returnAttribute($resContent, ['dispenseRequest', 'quantity'], null);
            $quantity = returnQuantity($dispenseQuantity, 'quantity', true);
            $supplyDuration = returnAttribute($resContent, ['dispenseRequest', 'expectedSupplyDuration'], null);
            $duration = returnDuration($supplyDuration, 'supply_duration');
            $substitution = returnAttribute($resContent, ['substitution'], null);
            $identifier = returnAttribute($resContent, ['identifier'], null);
            $category = returnAttribute($resContent, ['category'], null);
            $basedOn = returnAttribute($resContent, ['basedOn'], null);
            $insurance = returnAttribute($resContent, ['insurance'], null);
            $note = returnAttribute($resContent, ['note'], null);
            $dosage = returnAttribute($resContent, ['dosageInstruction'], null);

            $medReq = MedicationRequest::create(array_merge(
                [
                    'resource_id' => $mr->id,
                    'status' => returnAttribute($resContent, ['status'], 'unknown'),
                    'status_reason' => returnAttribute($resContent, ['statusReason', 'coding', 0, 'code'], null),
                    'priority' => returnAttribute($resContent, ['priority'], null),
                    'do_not_perform' => returnAttribute($resContent, ['doNotPerform'], null),
                    'reported' => returnAttribute($resContent, ['reportedBoolean'], null),
                    'medication' => returnAttribute($resContent, ['medicationReference', 'reference'], ''),
                    'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                    'encounter' => returnAttribute($resContent, ['encounter', 'reference'], null),
                    'authored_on' => returnAttribute($resContent, ['authoredOn'], null),
                    'requester' => returnAttribute($resContent, ['requester', 'reference'], null),
                    'performer' => returnAttribute($resContent, ['performer', 'reference'], null),
                    'performer_type' => returnAttribute($resContent, ['performerType', 'coding', 0, 'code'], null),
                    'recorder' => returnAttribute($resContent, ['recorder', 'reference'], null),
                    'course_of_therapy' => returnAttribute($resContent, ['courseOfTherapyType', 'coding', 0, 'code'], null),
                    'repeats_allowed' => returnAttribute($resContent, ['dispenseRequest', 'numberOfRepeatsAllowed'], null),
                    'dispense_performer' => returnAttribute($resContent, ['dispenseRequest', 'performer', 'reference'], null),
                    'substitution_allowed' => returnVariableAttribute($substitution, 'allowed', ['Boolean', 'CodeableConcept']),
                    'substitution_reason' => returnAttribute($substitution, ['reason', 'coding', 0, 'code'], null)
                ],
                $dispenseIntervalData,
                $validityPeriod,
                $quantity,
                $duration
            ));

            $foreignKey = ['med_req_id' => $medReq->id];

            parseAndCreate(MedicationRequestIdentifier::class, $identifier, 'returnIdentifier', $foreignKey);
            parseAndCreate(MedicationRequestCategory::class, $category, 'returnCodeableConcept', $foreignKey);
            parseAndCreateCompound(MedicationRequestReason::class, $resContent, ['reasonCode' => 'returnCodeableConcept', 'reasonReference' => 'returnReference'], $foreignKey);
            parseAndCreate(MedicationRequestBasedOn::class, $basedOn, 'returnReference', $foreignKey);
            parseAndCreate(MedicationRequestInsurance::class, $insurance, 'returnReference', $foreignKey);
            parseAndCreate(MedicationRequestNote::class, $note, 'returnAnnotation', $foreignKey);

            foreach ($dosage as $d) {
                $timing = returnTiming(returnAttribute($d, ['timing']), 'timing');
                $site = returnCodeableConcept(returnAttribute($d, ['site']), 'site');
                $route = returnCodeableConcept(returnAttribute($d, ['route']), 'route');
                $method = returnCodeableConcept(returnAttribute($d, ['method']), 'method');
                $maxDosePerPeriod = returnRatio(returnAttribute($d, ['maxDosePerPeriod']), 'max_dose_per_period');
                $maxDosePerAdministration = returnQuantity(returnAttribute($d, ['maxDosePerAdministration']), 'max_dose_per_administration', true);
                $maxDosePerLifetime = returnQuantity(returnAttribute($d, ['maxDosePerLifetime']), 'max_dose_per_lifetime', true);
                $additionalInstruction = returnAttribute($d, ['additionalInstruction']);
                $doseRate = returnAttribute($d, ['doseAndRate']);

                $dos = MedicationRequestDosage::create(
                // dd(
                    merge_array(
                        [
                            'med_req_id' => $medReq->id,
                            'sequence' => returnAttribute($d, ['sequence']),
                            'text' => returnAttribute($d, ['text']),
                            'patient_instruction' => returnAttribute($d, ['patientInstruction']),

                        ],
                        $timing,
                        $site,
                        $route,
                        $method,
                        $maxDosePerPeriod,
                        $maxDosePerAdministration,
                        $maxDosePerLifetime
                    )
                );

                $doseFk = ['med_req_dosage_id' => $dos->id];

                parseAndCreate(MedicationRequestDosageAdditionalInstruction::class, $additionalInstruction, 'returnCodeableConcept', $doseFk);
                parseAndCreate(MedicationRequestDosageDoseRate::class, $doseRate, 'returnDoseRate', $doseFk);
            }
        }
    }
}
