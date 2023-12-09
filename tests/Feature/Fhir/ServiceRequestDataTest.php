<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class ServiceRequestDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'servicerequest';

    /**
     * Test apakah user dapat menlihat data permintaan pelayanan medis
     */
    public function test_users_can_view_service_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data permintaan pelayanan medis baru
     */
    public function test_users_can_create_new_service_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('servicerequest');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('servicerequest.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('service_request', $data['serviceRequest']);
        $this->assertManyData('service_request_note', $data['note']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('service_request_identifier', ['system' => 'http://sys-ids.kemkes.go.id/servicerequest/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data permintaan pelayanan medis
     */
    public function test_users_can_update_service_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('servicerequest');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('servicerequest.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['serviceRequest']['id'] = $newData['id'];
        $data['serviceRequest']['resource_id'] = $newData['resource_id'];
        $data['serviceRequest']['priority'] = 'stat';
        $response = $this->json('PUT', route('servicerequest.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('service_request', $data['serviceRequest']);
        $this->assertManyData('service_request_note', $data['note']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('service_request_identifier', ['system' => 'http://sys-ids.kemkes.go.id/servicerequest/' . $orgId, 'use' => 'official']);
    }
}
