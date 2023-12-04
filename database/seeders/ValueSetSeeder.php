<?php

namespace Database\Seeders;

use Database\Seeders\Valuesets\{
    ConditionStageTypeSeeder,
    EncounterReasonCodeSeeder,
    MedicationIngredientStrengthDenominatorSeeder,
    ObservationRefRangeAppliesToSeeder,
    ProcedureFocalDeviceSeeder,
    ProcedurePerformerTypeSeeder,
    ProcedureReasonCodeSeeder,
    ProcedureStatusReasonSeeder,
    RiwayatPenyakitKeluargaSeeder,
    RiwayatPenyakitPribadiSeeder,
    SNOMEDCTBodySiteSeeder,
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
            ConditionStageTypeSeeder::class,
            EncounterReasonCodeSeeder::class,
            MedicationIngredientStrengthDenominatorSeeder::class,
            ObservationRefRangeAppliesToSeeder::class,
            ProcedureFocalDeviceSeeder::class,
            ProcedurePerformerTypeSeeder::class,
            ProcedureReasonCodeSeeder::class,
            ProcedureStatusReasonSeeder::class,
            RiwayatPenyakitKeluargaSeeder::class,
            RiwayatPenyakitPribadiSeeder::class,
            SNOMEDCTBodySiteSeeder::class,
        ]);
    }
}
