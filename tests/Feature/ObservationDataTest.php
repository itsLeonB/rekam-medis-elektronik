<?php

namespace Tests\Feature;

use App\Models\Observation;
use App\Models\Resource;
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

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'Observation',
                'res_ver' => 1
            ]
        );

        $observationData = array_merge(['resource_id' => $resource->id], $data['observation']);

        Observation::create($observationData);

        $response = $this->json('GET', 'api/observation/P000000');
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
        $this->assertManyData('observation_based_on', $data['based_on']);
        $this->assertManyData('observation_part_of', $data['part_of']);
        $this->assertManyData('observation_category', $data['category']);
        $this->assertManyData('observation_focus', $data['focus']);
        $this->assertManyData('observation_performer', $data['performer']);
        $this->assertManyData('observation_interpretation', $data['interpretation']);
        $this->assertManyData('observation_note', $data['note']);
        $this->assertManyData('observation_ref_range', $data['reference_range']);
        $this->assertManyData('observation_member', $data['has_member']);
        $this->assertManyData('observation_derived_from', $data['derived_from']);
        $this->assertNestedData('observation_component', $data['component'], 'component_data', [
            [
                'table' => 'obs_comp_interpret',
                'data' => 'interpretation'
            ],
            [
                'table' => 'obs_comp_ref_range',
                'data' => 'reference_range'
            ]
        ]);
    }
}
