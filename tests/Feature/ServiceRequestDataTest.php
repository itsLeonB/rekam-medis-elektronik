<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ServiceRequestDataTest extends TestCase
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
        $response = $this->json('POST', '/api/servicerequest/create', $data, $headers);
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
        $response = $this->json('POST', '/api/servicerequest/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('service_request', $data['serviceRequest']);
        $this->assertManyData('service_request_identifier', $data['identifier']);
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
        $response = $this->json('POST', '/api/servicerequest/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['serviceRequest']['id'] = $newData['id'];
        $data['serviceRequest']['resource_id'] = $newData['resource_id'];
        $data['serviceRequest']['priority'] = 'stat';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['request_id'] = $newData['identifier'][0]['request_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/servicerequest/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('service_request', $data['serviceRequest']);
        $this->assertManyData('service_request_identifier', $data['identifier']);
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
    }
}
