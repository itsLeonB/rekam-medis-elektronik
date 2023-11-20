<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
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
        Config::set('organization_id', env('organization_id'));

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
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
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
        $orgId = env('organization_id');
        $this->assertDatabaseHas('allergy_intolerance_identifier', ['system' => 'http://sys-ids.kemkes.go.id/allergy/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data alergi pasien
     */
    public function test_users_can_update_allergy_intolerance_data()
    {
        Config::set('organization_id', env('organization_id'));

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

        $response = $this->json('PUT', '/api/allergyintolerance/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
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
        $orgId = env('organization_id');
        $this->assertDatabaseHas('allergy_intolerance_identifier', ['system' => 'http://sys-ids.kemkes.go.id/allergy/' . $orgId, 'use' => 'official']);
    }
}
