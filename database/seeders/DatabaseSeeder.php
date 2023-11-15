<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => 'admin123'
        ]);

        $this->call([
            // UserSeeder::class,
            // WilayahSeeder::class,
            CodeSystemSeeder::class,
            ValueSetSeeder::class,
            // ResourceSeeder::class, // Resource FHIR R5
            // IdFhirResourceSeeder::class, // Resource example dari SATUSEHAT API
            // PractitionerSeeder::class,
            // PatientSeeder::class,
            // OrganizationSeeder::class,
            // LocationSeeder::class,
            // EncounterSeeder::class,
            // ConditionSeeder::class,
            // ObservationSeeder::class,
            // ImagingStudySeeder::class,
            // ProcedureSeeder::class,
            // MedicationSeeder::class,
            // MedicationRequestSeeder::class,
            // CompositionSeeder::class,
            // AllergyIntoleranceSeeder::class,
            // ClinicalImpressionSeeder::class,
            // ServiceRequestSeeder::class,
            // MedicationDispenseSeeder::class
        ]);
    }
}
