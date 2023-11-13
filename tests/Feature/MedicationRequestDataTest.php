<?php

namespace Tests\Feature;

use App\Models\MedicationRequest;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ExamplePayload;

class MedicationRequestDataTest extends TestCase
{
    use DatabaseTransactions;
    use ExamplePayload;

    /**
     * Test apakah user dapat menlihat data peresepan obat
     */
    public function test_users_can_view_medication_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationrequest');

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'MedicationRequest',
                'res_ver' => 1
            ]
        );

        $medicationData = array_merge(['resource_id' => $resource->id], $data['medication_request']);

        MedicationRequest::create($medicationData);

        $response = $this->json('GET', 'api/medicationrequest/P000000');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data peresepan obat baru
     */
    public function test_users_can_create_new_medication_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationrequest');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationrequest/create', $data, $headers);
        $response->assertStatus(201);
    }
}
