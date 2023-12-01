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

    /**
     * Test apakah user dapat menlihat data permintaan pelayanan medis
     */
    public function test_users_can_view_service_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('servicerequest');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/servicerequest', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/servicerequest/' . $newData['resource_id']);
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
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/servicerequest', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('service_request', $data['serviceRequest']);
        $this->assertManyData('service_request_based_on', $data['basedOn']);
        $this->assertManyData('service_request_replaces', $data['replaces']);
        $this->assertManyData('service_request_category', $data['category']);
        $this->assertManyData('service_request_order_detail', $data['orderDetail']);
        $this->assertManyData('service_request_performer', $data['performer']);
        $this->assertManyData('service_request_location', $data['location']);
        $this->assertManyData('service_request_reason', $data['reason']);
        $this->assertManyData('service_request_insurance', $data['insurance']);
        $this->assertManyData('service_request_supporting_info', $data['supportingInfo']);
        $this->assertManyData('service_request_specimen', $data['specimen']);
        $this->assertManyData('service_request_body_site', $data['bodySite']);
        $this->assertManyData('service_request_note', $data['note']);
        $this->assertManyData('service_request_relevant_history', $data['relevantHistory']);
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
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/servicerequest', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['serviceRequest']['id'] = $newData['id'];
        $data['serviceRequest']['resource_id'] = $newData['resource_id'];
        $data['serviceRequest']['priority'] = 'stat';
        $response = $this->json('PUT', '/api/servicerequest/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('service_request', $data['serviceRequest']);
        $this->assertManyData('service_request_based_on', $data['basedOn']);
        $this->assertManyData('service_request_replaces', $data['replaces']);
        $this->assertManyData('service_request_category', $data['category']);
        $this->assertManyData('service_request_order_detail', $data['orderDetail']);
        $this->assertManyData('service_request_performer', $data['performer']);
        $this->assertManyData('service_request_location', $data['location']);
        $this->assertManyData('service_request_reason', $data['reason']);
        $this->assertManyData('service_request_insurance', $data['insurance']);
        $this->assertManyData('service_request_supporting_info', $data['supportingInfo']);
        $this->assertManyData('service_request_specimen', $data['specimen']);
        $this->assertManyData('service_request_body_site', $data['bodySite']);
        $this->assertManyData('service_request_note', $data['note']);
        $this->assertManyData('service_request_relevant_history', $data['relevantHistory']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('service_request_identifier', ['system' => 'http://sys-ids.kemkes.go.id/servicerequest/' . $orgId, 'use' => 'official']);
    }
}
