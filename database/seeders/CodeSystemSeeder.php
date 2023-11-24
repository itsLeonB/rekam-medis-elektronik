<?php

namespace Database\Seeders;

use Database\Seeders\Codesystems\{
    AdministrativeCodeSeeder,
    EncounterReasonSeeder,
    ICD10Seeder,
    ICD9CMProcedureSeeder,
    LoincSeeder,
    ServiceTypeSeeder,
};
use Illuminate\Database\Seeder;

class CodeSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdministrativeCodeSeeder::class,
            EncounterReasonSeeder::class,
            ICD9CMProcedureSeeder::class,
            ICD10Seeder::class,
            LoincSeeder::class,
            ServiceTypeSeeder::class,
        ]);
    }
}
