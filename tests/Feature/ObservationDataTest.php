<?php

namespace Tests\Feature;

use App\Models\Observation;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\ExamplePayload;

class ObservationDataTest extends TestCase
{
    use DatabaseTransactions;
    use ExamplePayload;

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
    }
}
