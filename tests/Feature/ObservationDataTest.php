<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ObservationDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data observasi
     */
    public function test_users_can_view_observation_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('observation');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/observation/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/observation/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data observasi baru
     */
    public function test_users_can_create_new_observation_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('observation');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/observation/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('observation', $data['observation']);
        $this->assertManyData('observation_identifier', $data['identifier']);
        $this->assertManyData('observation_based_on', $data['basedOn']);
        $this->assertManyData('observation_part_of', $data['partOf']);
        $this->assertManyData('observation_category', $data['category']);
        $this->assertManyData('observation_focus', $data['focus']);
        $this->assertManyData('observation_performer', $data['performer']);
        $this->assertManyData('observation_interpretation', $data['interpretation']);
        $this->assertManyData('observation_note', $data['note']);
        $this->assertManyData('observation_ref_range', $data['referenceRange']);
        $this->assertManyData('observation_member', $data['member']);
        $this->assertManyData('observation_derived_from', $data['derivedFrom']);
        $this->assertNestedData('observation_component', $data['component'], 'component_data', [
            [
                'table' => 'obs_comp_interpret',
                'data' => 'interpretation'
            ],
            [
                'table' => 'obs_comp_ref_range',
                'data' => 'referenceRange'
            ]
        ]);
    }


    /**
     * Test apakah user dapat memperbarui data observasi
     */
    public function test_users_can_update_observation_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('observation');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/observation/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['observation']['id'] = $newData['id'];
        $data['observation']['resource_id'] = $newData['resource_id'];
        $data['observation']['subject'] = 'Patient/10001';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['observation_id'] = $newData['identifier'][0]['observation_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/observation/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('observation', $data['observation']);
        $this->assertManyData('observation_identifier', $data['identifier']);
        $this->assertManyData('observation_based_on', $data['basedOn']);
        $this->assertManyData('observation_part_of', $data['partOf']);
        $this->assertManyData('observation_category', $data['category']);
        $this->assertManyData('observation_focus', $data['focus']);
        $this->assertManyData('observation_performer', $data['performer']);
        $this->assertManyData('observation_interpretation', $data['interpretation']);
        $this->assertManyData('observation_note', $data['note']);
        $this->assertManyData('observation_ref_range', $data['referenceRange']);
        $this->assertManyData('observation_member', $data['member']);
        $this->assertManyData('observation_derived_from', $data['derivedFrom']);
        $this->assertNestedData('observation_component', $data['component'], 'component_data', [
            [
                'table' => 'obs_comp_interpret',
                'data' => 'interpretation'
            ],
            [
                'table' => 'obs_comp_ref_range',
                'data' => 'referenceRange'
            ]
        ]);
    }
}
