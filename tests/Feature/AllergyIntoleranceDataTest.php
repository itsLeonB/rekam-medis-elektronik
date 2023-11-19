<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class AllergyIntoleranceDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data alergi pasien
     */
    public function test_users_can_view_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/allergyintolerance/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data alergi pasien baru
     */
    public function test_users_can_create_new_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
        $this->assertManyData('allergy_intolerance_identifier', $data['identifier']);
        $this->assertManyData('allergy_intolerance_note', $data['note']);
        $this->assertNestedData('allergy_intolerance_reaction', $data['reaction'], 'reaction_data', [
            [
                'table' => 'allergy_react_manifest',
                'data' => 'manifestation'
            ],
            [
                'table' => 'allergy_react_note',
                'data' => 'note'
            ]
        ]);
    }


    /**
     * Test apakah user dapat memperbarui data alergi pasien
     */
    public function test_users_can_update_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['allergyIntolerance']['id'] = $newData['id'];
        $data['allergyIntolerance']['resource_id'] = $newData['resource_id'];
        $data['allergyIntolerance']['type'] = 'intolerance';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['allergy_id'] = $newData['identifier'][0]['allergy_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/allergyintolerance/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
        $this->assertManyData('allergy_intolerance_identifier', $data['identifier']);
        $this->assertManyData('allergy_intolerance_note', $data['note']);
        $this->assertNestedData('allergy_intolerance_reaction', $data['reaction'], 'reaction_data', [
            [
                'table' => 'allergy_react_manifest',
                'data' => 'manifestation'
            ],
            [
                'table' => 'allergy_react_note',
                'data' => 'note'
            ]
        ]);
    }


    public function test_identifier_is_auto_incremented_on_create()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');
        unset($data['identifier']);
        $orgId = env('organization_id');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance', $data, $headers);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
        $this->assertDatabaseHas('allergy_intolerance_identifier', ['system' => 'http://sys-ids.kemkes.go.id/allergy/' . $orgId, 'use' => 'official']);
    }
}
