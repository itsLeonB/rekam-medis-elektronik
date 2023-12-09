<?php

namespace Database\Seeders;

use Database\Seeders\Codesystems\{
    AdministrativeAreaSeeder,
    BCP47Seeder,
    ClinicalSpecialtySeeder,
    ICD10Seeder,
    ICD9CMProcedureSeeder,
    ISO3166Seeder,
    LoincSeeder,
    ServiceTypeSeeder,
    UCUMSeeder,
    V3ActCodeSeeder,
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
            AdministrativeAreaSeeder::class,
            BCP47Seeder::class,
            ClinicalSpecialtySeeder::class,
            ICD9CMProcedureSeeder::class,
            ICD10Seeder::class,
            ISO3166Seeder::class,
            LoincSeeder::class,
            ServiceTypeSeeder::class,
            UCUMSeeder::class,
            V3ActCodeSeeder::class,
        ]);
    }
}
