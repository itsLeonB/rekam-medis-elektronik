<?php

namespace Database\Seeders;

use App\Models\MedicationDispense;
use App\Models\MedicationDispenseAuthorizingPrescription;
use App\Models\MedicationDispenseDosageInstruction;
use App\Models\MedicationDispenseDosageInstructionAdditionalInstruction;
use App\Models\MedicationDispenseDosageInstructionDoseRate;
use App\Models\MedicationDispenseIdentifier;
use App\Models\MedicationDispensePartOf;
use App\Models\MedicationDispensePerformer;
use App\Models\MedicationDispenseSubstitution;
use App\Models\MedicationDispenseSubstitutionReason;
use App\Models\MedicationDispenseSubstitutionResponsibleParty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resource;

class MedicationDispenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicationDispenses = Resource::join('resource_content', function ($join) {
            $join->on('resource.id', '=', 'resource_content.resource_id')
                ->whereColumn('resource.res_version', '=', 'resource_content.res_ver');
        })->where('resource.res_type', 'MedicationDispense')
            ->select('resource.*', 'resource_content.res_text')
            ->get();

        foreach ($medicationDispenses as $md) {
            $resContent = json_decode($md->res_text, true);

            $dispense = MedicationDispense::create(merge_array(
                [
                    'resource_id' => $md->id,
                    'status' => returnAttribute($resContent, ['status'], 'unknown'),
                    'category' => returnAttribute($resContent, ['category', 'coding', 0, 'code']),
                    'medication' => returnAttribute($resContent, ['medicationReference', 'reference'], ''),
                    'subject' => returnAttribute($resContent, ['subject', 'reference'], ''),
                    'context' => returnAttribute($resContent, ['context', 'reference']),
                    'location' => returnAttribute($resContent, ['location', 'reference']),
                    'when_prepared' => returnAttribute($resContent, ['whenPrepared']),
                    'when_handed_over' => returnAttribute($resContent, ['whenHandedOver'])
                ],
                returnQuantity(returnattribute($resContent, ['quantity']), 'quantity', true),
                returnQuantity(returnAttribute($resContent, ['daysSupply']), 'days_supply', true),
            ));

            $fk = ['dispense_id' => $dispense->id];

            parseAndCreate(MedicationDispenseIdentifier::class, returnAttribute($resContent, ['identifier']), 'returnIdentifier', $fk);
            parseAndCreate(MedicationDispensePartOf::class, returnAttribute($resContent, ['partOf']), 'returnReference', $fk);
            parseAndCreate(MedicationDispensePerformer::class, returnAttribute($resContent, ['performer']), 'returnPerformer', $fk);
            parseAndCreate(MedicationDispenseAuthorizingPrescription::class, returnAttribute($resContent, ['authorizingPrescription']), 'returnReference', $fk);

            $dosage = returnAttribute($resContent, ['dosageInstruction']);

            if (is_array($dosage) || is_object($dosage)) {
                foreach ($dosage as $d) {
                    $di = MedicationDispenseDosageInstruction::create(merge_array(
                        $fk,
                        [
                            'sequence' => returnAttribute($d, ['sequence']),
                            'text' => returnAttribute($d, ['text']),
                            'patient_instruction' => returnAttribute($d, ['patientInstruction']),
                        ],
                        returnTiming(returnAttribute($d, ['timing']), 'timing'),
                        returnCodeableConcept(returnAttribute($d, ['site']), 'site'),
                        returnCodeableConcept(returnAttribute($d, ['route']), 'route'),
                        returnCodeableConcept(returnAttribute($d, ['method']), 'method'),
                        returnRatio(returnAttribute($d, ['maxDosePerPeriod']), 'max_dose_per_period'),
                        returnQuantity(returnAttribute($d, ['maxDosePerAdministration']), 'max_dose_per_administration', true),
                        returnQuantity(returnAttribute($d, ['maxDosePerLifetime']), 'max_dose_per_lifetime', true)
                    ));

                    $diFk = ['med_disp_dose_id' => $di->id];

                    parseAndCreate(MedicationDispenseDosageInstructionAdditionalInstruction::class, returnAttribute($d, ['additionalInstruction']), 'returnCodeableConcept', $diFk);
                    parseAndCreate(MedicationDispenseDosageInstructionDoseRate::class, returnAttribute($d, ['doseAndRate']), 'returnDoseRate', $diFk);
                }
            }

            $substitution = returnAttribute($resContent, ['substitution']);

            if (is_array($substitution) || is_object($substitution)) {
                foreach ($substitution as $s) {
                    $mds = MedicationDispenseSubstitution::create(merge_array(
                        $fk,
                        ['was_substituted' => returnAttribute($s, ['wasSubstituted'])],
                        returnCodeableConcept(returnAttribute($s, ['type']), 'type')
                    ));

                    $mdsFk = ['med_disp_subs_id' => $mds->id];

                    parseAndCreate(MedicationDispenseSubstitutionReason::class, returnAttribute($s, ['reason']), 'returnCodeableConcept', $mdsFk);
                    parseAndCreate(MedicationDispenseSubstitutionResponsibleParty::class, returnAttribute($s, ['responsibleParty']), 'returnReference', $mdsFk);
                }
            }
        }
    }
}
