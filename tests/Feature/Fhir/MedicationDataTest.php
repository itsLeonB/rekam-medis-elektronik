<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class MedicationDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'medication';

    /**
     * Test apakah user dapat menlihat data obat
     */
    public function test_users_can_view_medication_data()
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
     * Test apakah user dapat membuat data obat baru
     */
    public function test_users_can_create_new_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('medication', 1);
        $this->assertDatabaseCount('identifiers', 2);
        $this->assertDatabaseCount('codeable_concepts', 7);
        $this->assertDatabaseCount('codings', 7);
        $this->assertDatabaseCount('references', 1);
        $this->assertDatabaseCount('medication_ingredient', 4);
        $this->assertDatabaseCount('ratios', 4);
        $this->assertDatabaseCount('quantities', 8);
        $this->assertDatabaseCount('extensions', 1);
    }


    /**
     * Test apakah user dapat memperbarui data obat
     */
    public function test_users_can_update_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['manufacturer']['display'] = 'Organisasi update';
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route(self::RESOURCE_TYPE . '.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 2);
        $this->assertDatabaseCount('medication', 1);
        $this->assertDatabaseCount('identifiers', 2);
        $this->assertDatabaseCount('codeable_concepts', 7);
        $this->assertDatabaseCount('codings', 7);
        $this->assertDatabaseCount('references', 1);
        $this->assertDatabaseCount('medication_ingredient', 4);
        $this->assertDatabaseCount('ratios', 4);
        $this->assertDatabaseCount('quantities', 8);
        $this->assertDatabaseCount('extensions', 1);
    }
}
