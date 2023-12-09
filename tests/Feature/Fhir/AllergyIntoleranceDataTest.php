<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class AllergyIntoleranceDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'allergyintolerance';

    /**
     * Test apakah user dapat menlihat data alergi pasien
     */
    public function test_users_can_view_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE. '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE. '.show', ['res_id' => $newData['resource_id']]));
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
        $response = $this->json('POST', route('allergyintolerance.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
        $this->assertManyData('allergy_intolerance_note', $data['note']);
        $this->assertNestedData('allergy_intolerance_reaction', $data['reaction'], 'reaction_data', [
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route('allergyintolerance.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['allergyIntolerance']['id'] = $newData['id'];
        $data['allergyIntolerance']['resource_id'] = $newData['resource_id'];
        $data['allergyIntolerance']['type'] = 'intolerance';

        $response = $this->json('PUT', route('allergyintolerance.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('allergy_intolerance', $data['allergyIntolerance']);
        $this->assertManyData('allergy_intolerance_note', $data['note']);
        $this->assertNestedData('allergy_intolerance_reaction', $data['reaction'], 'reaction_data', [
            [
                'table' => 'allergy_react_note',
                'data' => 'note'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('allergy_intolerance_identifier', ['system' => 'http://sys-ids.kemkes.go.id/allergy/' . $orgId, 'use' => 'official']);
    }
}
