<?php

namespace Database\Seeders;

use Database\Seeders\Valuesets\{
    ConditionBodySiteSeeder,
    ConditionStageTypeSeeder,
    EncounterReasonCodeSeeder,
    MedicationIngredientStrengthDenominatorSeeder,
    ObservationRefRangeAppliesToSeeder,
    ObservationValueQuantitySeeder,
    ProcedureFocalDeviceSeeder,
    ProcedurePerformerTypeSeeder,
    ProcedureReasonCodeSeeder,
    ProcedureStatusReasonSeeder,
    RiwayatPenyakitKeluargaSeeder,
    RiwayatPenyakitPribadiSeeder,
};
use Illuminate\Database\Seeder;

class ValueSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ConditionBodySiteSeeder::class,
            ConditionStageTypeSeeder::class,
            EncounterReasonCodeSeeder::class,
            MedicationIngredientStrengthDenominatorSeeder::class,
            ObservationRefRangeAppliesToSeeder::class,
            ObservationValueQuantitySeeder::class,
            ProcedureFocalDeviceSeeder::class,
            ProcedurePerformerTypeSeeder::class,
            ProcedureReasonCodeSeeder::class,
            ProcedureStatusReasonSeeder::class,
            RiwayatPenyakitKeluargaSeeder::class,
            RiwayatPenyakitPribadiSeeder::class,
        ]);
    }
}
