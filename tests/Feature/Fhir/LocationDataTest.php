<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class LocationDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data lokasi
     */
    public function test_users_can_view_location_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('location');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('location.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route('resource.show', ['res_type' => 'location', 'res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data lokasi baru
     */
    public function test_users_can_create_new_location_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('location');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('location.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('location', $data['location']);
        $this->assertManyData('location_identifier', $data['identifier']);
        $this->assertManyData('location_telecom', $data['telecom']);
        $this->assertManyData('location_operation_hours', $data['operationHours']);
    }


    /**
     * Test apakah user dapat memperbarui data lokasi
     */
    public function test_users_can_update_location_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('location');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('location.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['location']['id'] = $newData['id'];
        $data['location']['resource_id'] = $newData['resource_id'];
        $data['location']['status'] = 'inactive';
        $response = $this->json('PUT', route('location.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('location', $data['location']);
        $this->assertManyData('location_identifier', $data['identifier']);
        $this->assertManyData('location_telecom', $data['telecom']);
        $this->assertManyData('location_operation_hours', $data['operationHours']);
    }
}
