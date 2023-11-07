<?php

namespace Tests\Feature;

use App\Models\Medication;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\ExamplePayload;

class MedicationDataTest extends TestCase
{
    use DatabaseTransactions;
    use ExamplePayload;

    /**
     * Test apakah user dapat menlihat data obat
     */
    public function test_users_can_view_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'Medication',
                'res_ver' => 1
            ]
        );

        $medicationData = array_merge(['resource_id' => $resource->id], $data['medication']);

        Medication::create($medicationData);

        $response = $this->json('GET', 'api/medication/P000000');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data obat baru
     */
    public function test_users_can_create_new_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medication/create', $data, $headers);
        $response->assertStatus(201);
    }
}
