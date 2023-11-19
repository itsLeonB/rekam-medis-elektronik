<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class MedicationDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data obat
     */
    public function test_users_can_view_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medication/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/medication/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data obat baru
     */
    public function test_users_can_create_new_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medication/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('medication', $data['medication']);
        $this->assertManyData('medication_identifier', $data['identifier']);
        $this->assertManyData('medication_ingredient', $data['ingredient']);
    }


    /**
     * Test apakah user dapat memperbarui data obat
     */
    public function test_users_can_update_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medication/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['medication']['id'] = $newData['id'];
        $data['medication']['resource_id'] = $newData['resource_id'];
        $data['medication']['status'] = 'inactive';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['medication_id'] = $newData['identifier'][0]['medication_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/medication/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('medication', $data['medication']);
        $this->assertManyData('medication_identifier', $data['identifier']);
        $this->assertManyData('medication_ingredient', $data['ingredient']);
    }
}
