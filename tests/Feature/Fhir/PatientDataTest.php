<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class PatientDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'patient';

    /**
     * Test apakah user dapat menlihat data pasien
     */
    public function test_users_can_view_patient_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['satusehat_id' => $newData['id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data pasien baru
     */
    public function test_users_can_create_new_patient_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);

        $response->assertStatus(201);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('patient', 1);
        $this->assertDatabaseCount('extensions', 8);
        $this->assertDatabaseCount('address', 2);
        $this->assertDatabaseCount('complex_extensions', 1);
        $this->assertDatabaseCount('patient_communication', 1);
        $this->assertDatabaseCount('codeable_concepts', 3);
        $this->assertDatabaseCount('codings', 3);
        $this->assertDatabaseCount('patient_contact', 1);
        $this->assertDatabaseCount('human_names', 2);
        $this->assertDatabaseCount('contact_points', 3);
        $this->assertDatabaseCount('identifiers', 3);
    }
}
