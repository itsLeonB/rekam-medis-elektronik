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
    // public function test_id_fhir_seeder(): void
    // {
    //     $seeder = new IdFhirResourceSeeder();
    //     $seeder->run();
    //     $this->assertTrue(true);
    // }

    public function test_dummy_seeder(): void
    {
        $seeder = new DummyDataSeeder();
        $seeder->run();
        $this->assertTrue(true);
    }
}
