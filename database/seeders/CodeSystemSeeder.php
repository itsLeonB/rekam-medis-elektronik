<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            CodeSystemDischargeDispositionSeeder::class,
            CodeSystemEncounterReasonSeeder::class,
            CodeSystemParticipantTypeSeeder::class,
            CodeSystemServiceTypeSeeder::class,
        ]);
    }
}
