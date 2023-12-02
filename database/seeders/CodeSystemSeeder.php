<?php

namespace Database\Seeders;

use Database\Seeders\Codesystems\{
    AdministrativeCodeSeeder,
    BCP47Seeder,
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
            BCP47Seeder::class,
            EncounterReasonSeeder::class,
            ICD9CMProcedureSeeder::class,
            ICD10Seeder::class,
            ISO3166Seeder::class,
            LoincSeeder::class,
            ServiceTypeSeeder::class,
        ]);
    }
}
