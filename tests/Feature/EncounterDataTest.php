<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class EncounterDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data kunjungan pasien
     */
    public function test_users_can_view_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('encounter');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/encounter', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/encounter/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data kunjungan pasien baru
     */
    public function test_users_can_create_new_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('encounter');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/encounter', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('encounter', $data['encounter']);
        $this->assertManyData('encounter_identifier', $data['identifier']);
        $this->assertManyData('encounter_status_history', $data['statusHistory']);
        $this->assertManyData('encounter_class_history', $data['classHistory']);
        $this->assertManyData('encounter_participant', $data['participant']);
        $this->assertManyData('encounter_reason', $data['reason']);
        $this->assertManyData('encounter_diagnosis', $data['diagnosis']);
        $this->assertNestedData('encounter_hospitalization', $data['hospitalization'], 'hospitalization_data', [
            [
                'table' => 'encounter_hospitalization_diet',
                'data' => 'diet'
            ],
            [
                'table' => 'encounter_hospitalization_spc_arr',
                'data' => 'specialArrangement'
            ]
        ]);
    }


    /**
     * Test apakah user dapat memperbarui data kunjungan pasien
     */
    public function test_users_can_update_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('encounter');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/encounter', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['encounter']['id'] = $newData['id'];
        $data['encounter']['resource_id'] = $newData['resource_id'];
        $data['encounter']['status'] = 'planned';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['encounter_id'] = $newData['identifier'][0]['encounter_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/encounter/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);
        $updatedResponse = $this->json('GET', '/api/encounter/' . $newData['resource_id']);
        $updatedData = json_decode($updatedResponse->getContent(), true);
        $this->assertEquals('planned', $updatedData['status']);
        $this->assertEquals('1234567890', $updatedData['identifier'][1]['value']);

        $this->assertMainData('encounter', $data['encounter']);
        $this->assertManyData('encounter_identifier', $data['identifier']);
        $this->assertManyData('encounter_status_history', $data['statusHistory']);
        $this->assertManyData('encounter_class_history', $data['classHistory']);
        $this->assertManyData('encounter_participant', $data['participant']);
        $this->assertManyData('encounter_reason', $data['reason']);
        $this->assertManyData('encounter_diagnosis', $data['diagnosis']);
        $this->assertNestedData('encounter_hospitalization', $data['hospitalization'], 'hospitalization_data', [
            [
                'table' => 'encounter_hospitalization_diet',
                'data' => 'diet'
            ],
            [
                'table' => 'encounter_hospitalization_spc_arr',
                'data' => 'specialArrangement'
            ]
        ]);
    }
}
