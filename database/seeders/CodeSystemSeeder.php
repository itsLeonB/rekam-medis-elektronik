<?php

namespace Database\Seeders;

use Database\Seeders\Codesystems\{
    AdministrativeAreaSeeder,
    Bcp13Seeder,
    Bcp47Seeder,
    ClinicalSpecialtySeeder,
    Icd10Seeder,
    Icd9CmProcedureSeeder,
    Iso3166Seeder,
    LoincSeeder,
    ServiceTypeSeeder,
    UcumSeeder,
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
            Bcp13Seeder::class,
            Bcp47Seeder::class,
            ClinicalSpecialtySeeder::class,
            Icd9CmProcedureSeeder::class,
            Icd10Seeder::class,
            Iso3166Seeder::class,
            LoincSeeder::class,
            ServiceTypeSeeder::class,
            UcumSeeder::class,
            V3ActCodeSeeder::class,
        ]);
    }
}
