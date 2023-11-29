<?php

namespace Database\Seeders;

use Database\Seeders\Codesystems\{
    AdministrativeCodeSeeder,
    EncounterReasonSeeder,
    ICD10Seeder,
    ICD9CMProcedureSeeder,
    ISO3166Seeder,
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
            ISO3166Seeder::class
        ]);
    }
}
