<?php

namespace Tests\Feature;

use App\Models\Resource;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'ServiceRequest',
                'res_ver' => 1
            ]
        );

        $serviceRequestData = array_merge(['resource_id' => $resource->id], $data['service_request']);

        ServiceRequest::create($serviceRequestData);

        $response = $this->json('GET', 'api/servicerequest/P000000');
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

        $this->assertMainData('service_request', $data['service_request']);
        $this->assertManyData('service_request_identifier', $data['identifier']);
        $this->assertManyData('service_request_based_on', $data['based_on']);
        $this->assertManyData('service_request_replaces', $data['replaces']);
        $this->assertManyData('service_request_category', $data['category']);
        $this->assertManyData('service_request_order_detail', $data['order_detail']);
        $this->assertManyData('service_request_performer', $data['performer']);
        $this->assertManyData('service_request_location', $data['location']);
        $this->assertManyData('service_request_reason', $data['reason']);
        $this->assertManyData('service_request_insurance', $data['insurance']);
        $this->assertManyData('service_request_supporting_info', $data['supporting_info']);
        $this->assertManyData('service_request_specimen', $data['specimen']);
        $this->assertManyData('service_request_body_site', $data['body_site']);
        $this->assertManyData('service_request_note', $data['note']);
        $this->assertManyData('service_request_relevant_history', $data['relevant_history']);
    }
}
