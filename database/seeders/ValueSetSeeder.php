<?php

namespace Database\Seeders;

use Database\Seeders\Valuesets\{
    AllergyReactionSubstanceSeeder,
    ConditionStageTypeSeeder,
    EncounterReasonCodeSeeder,
    MedicationIngredientStrengthDenominatorSeeder,
    ObservationRefRangeAppliesToSeeder,
    ParticipantRolesSeeder,
    ProcedureDeviceActionCodesSeeder,
    ProcedureNotPerformedReasonSeeder,
    ProcedurePerformerRoleCodesSeeder,
    RiwayatPenyakitKeluargaSeeder,
    RiwayatPenyakitPribadiSeeder,
    SnomedCtBodySiteSeeder,
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
            AllergyReactionSubstanceSeeder::class,
            ConditionStageTypeSeeder::class,
            EncounterReasonCodeSeeder::class,
            MedicationIngredientStrengthDenominatorSeeder::class,
            ObservationRefRangeAppliesToSeeder::class,
            ParticipantRolesSeeder::class,
            ProcedureDeviceActionCodesSeeder::class,
            ProcedureNotPerformedReasonSeeder::class,
            ProcedurePerformerRoleCodesSeeder::class,
            RiwayatPenyakitKeluargaSeeder::class,
            RiwayatPenyakitPribadiSeeder::class,
            SnomedCtBodySiteSeeder::class,
        ]);
    }
}
