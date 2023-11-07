<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ValueSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ValueSetObservationValueQuantitySeeder::class,
            ValueSetProcedureReasonCodeSeeder::class,
            ValueSetConditionCodeKeluhanUtamaSeeder::class,
            ValueSetConditionCodeRiwayatPenyakitPribadiSeeder::class,
            ValueSetConditionCodeRiwayatPenyakitKeluargaSeeder::class,
            ValueSetProcedurePerformerTypeSeeder::class,
            ValueSetProcedureFocalDeviceSeeder::class,
        ]);
    }
}
