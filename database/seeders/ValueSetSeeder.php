<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        ]);
    }
}
