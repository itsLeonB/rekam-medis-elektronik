<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => 'admin123'
        ]);

        $this->call([
            UserSeeder::class,
            WilayahSeeder::class,
            // ResourceSeeder::class, // Resource FHIR R5
            IdFhirResourceSeeder::class, // Resource example dari SATUSEHAT API
            PractitionerSeeder::class,
            PatientSeeder::class,
            OrganizationSeeder::class,
            LocationSeeder::class,
            EncounterSeeder::class,
            ConditionSeeder::class,
            ObservationSeeder::class,
            ImagingStudySeeder::class,
        ]);
    }
}
