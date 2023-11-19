<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ConditionDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data kondisi pasien
     */
    public function test_users_can_view_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('condition');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/condition/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/condition/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data kondisi pasien baru
     */
    public function test_users_can_create_new_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('condition');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/condition/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('condition', $data['condition']);
        $this->assertManyData('condition_identifier', $data['identifier']);
        $this->assertManyData('condition_category', $data['category']);
        $this->assertManyData('condition_body_site', $data['bodySite']);
        $this->assertNestedData('condition_stage', $data['stage'], 'stage_data', [
            [
                'table' => 'condition_stage_assessment',
                'data' => 'assessment'
            ]
        ]);
        $this->assertManyData('condition_evidence', $data['evidence']);
        $this->assertManyData('condition_note', $data['note']);
    }


    /**
     * Test apakah user dapat memperbarui data kondisi pasien
     */
    public function test_users_can_update_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('condition');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/condition/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['condition']['id'] = $newData['id'];
        $data['condition']['resource_id'] = $newData['resource_id'];
        $data['condition']['verification_status'] = 'confirmed';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['condition_id'] = $newData['identifier'][0]['condition_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/condition/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('condition', $data['condition']);
        $this->assertManyData('condition_identifier', $data['identifier']);
        $this->assertManyData('condition_category', $data['category']);
        $this->assertManyData('condition_body_site', $data['bodySite']);
        $this->assertNestedData('condition_stage', $data['stage'], 'stage_data', [
            [
                'table' => 'condition_stage_assessment',
                'data' => 'assessment'
            ]
        ]);
        $this->assertManyData('condition_evidence', $data['evidence']);
        $this->assertManyData('condition_note', $data['note']);
    }
}
