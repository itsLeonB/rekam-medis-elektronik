<?php

namespace Database\Seeders;

use Database\Seeders\Valuesets\{
    ConditionCodeKeluhanUtamaSeeder,
    ConditionCodeRiwayatPenyakitKeluargaSeeder,
    ConditionCodeRiwayatPenyakitPribadiSeeder,
    ConditionStageTypeSeeder,
    MedicationIngredientStrengthDenominatorSeeder,
    ObservationRefRangeAppliesToSeeder,
    ObservationValueQuantitySeeder,
    ProcedureFocalDeviceSeeder,
    ProcedurePerformerTypeSeeder,
    ProcedureReasonCodeSeeder,
    ProcedureStatusReasonSeeder,
    SpecimenContainerTypeSeeder,
    SpecimenTypeSeeder,
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
            ConditionCodeKeluhanUtamaSeeder::class,
            ConditionCodeRiwayatPenyakitKeluargaSeeder::class,
            ConditionCodeRiwayatPenyakitPribadiSeeder::class,
            ConditionStageTypeSeeder::class,
            MedicationIngredientStrengthDenominatorSeeder::class,
            ObservationRefRangeAppliesToSeeder::class,
            ObservationValueQuantitySeeder::class,
            ProcedureFocalDeviceSeeder::class,
            ProcedurePerformerTypeSeeder::class,
            ProcedureReasonCodeSeeder::class,
            ProcedureReasonCodeSeeder::class,
            ProcedureStatusReasonSeeder::class,
            SpecimenContainerTypeSeeder::class,
            SpecimenTypeSeeder::class,
        ]);
    }
}
