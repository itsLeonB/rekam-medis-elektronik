<?php

namespace Tests\Feature;

use App\Models\AllergyIntolerance;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'AllergyIntolerance',
                'res_ver' => 1
            ]
        );

        $allergyData = array_merge(['resource_id' => $resource->id], $data['allergy_intolerance']);

        AllergyIntolerance::create($allergyData);

        $response = $this->json('GET', 'api/allergyintolerance/000001');
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
        $response = $this->json('POST', '/api/allergyintolerance/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('allergy_intolerance', $data['allergy_intolerance']);
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
        $response = $this->json('POST', '/api/allergyintolerance/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['allergy_intolerance']['id'] = $newData['id'];
        $data['allergy_intolerance']['resource_id'] = $newData['resource_id'];
        $data['allergy_intolerance']['type'] = 'intolerance';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['allergy_id'] = $newData['identifier'][0]['allergy_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('POST', '/api/allergyintolerance/update', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('allergy_intolerance', $data['allergy_intolerance']);
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
}
