<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CodeSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            CodeSystemAdmitSourceSeeder::class,
            CodeSystemClinicalStatusSeeder::class,
            CodeSystemDischargeDispositionSeeder::class,
            CodeSystemEncounterReasonSeeder::class,
            CodeSystemICD10Seeder::class,
            CodeSystemParticipantTypeSeeder::class,
            CodeSystemServiceTypeSeeder::class,
            CodeSystemVerificationStatusSeeder::class,
            CodeSystemLoincSeeder::class,
        ]);
    }
}
