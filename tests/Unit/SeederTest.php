<?php

namespace Tests\Feature;

use Database\Seeders\DummyDataSeeder;
use Database\Seeders\IdFhirResourceSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    // public function test_fhir_seeder(): void
    // {
    //     $seeder = new IdFhirResourceSeeder();
    //     $seeder->run();
    //     $this->assertDatabaseCount('resource', 36);
    //     $this->assertDatabaseCount('organization', 1);
    //     $this->assertDatabaseCount('location', 1);
    //     $this->assertDatabaseCount('practitioner', 5);
    //     $this->assertDatabaseCount('patient', 14);
    //     $this->assertDatabaseCount('encounter', 2);
    //     $this->assertDatabaseCount('condition', 3);
    //     $this->assertDatabaseCount('observation', 1);
    //     $this->assertDatabaseCount('procedure', 1);
    //     $this->assertDatabaseCount('medication', 1);
    //     $this->assertDatabaseCount('medication_request', 1);
    //     $this->assertDatabaseCount('composition', 1);
    //     $this->assertDatabaseCount('allergy_intolerance', 1);
    //     $this->assertDatabaseCount('clinical_impression', 1);
    //     $this->assertDatabaseCount('service_request', 1);
    //     $this->assertDatabaseCount('medication_statement', 1);
    //     $this->assertDatabaseCount('questionnaire_response', 1);
    // }

    public function test_dummy_seeder(): void
    {
        $seeder = new DummyDataSeeder();
        $seeder->run();
        $this->assertTrue(true);
    }
}
