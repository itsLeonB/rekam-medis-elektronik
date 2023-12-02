<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class OrganizationDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data organisasi
     */
    public function test_users_can_view_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('organization');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('organization.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route('resource.show', ['res_type' => 'organization', 'res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data organisasi baru
     */
    public function test_users_can_create_new_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('organization');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('organization.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('organization', $data['organization']);
        $this->assertManyData('organization_identifier', $data['identifier']);
        $this->assertManyData('organization_telecom', $data['telecom']);
        $this->assertManyData('organization_address', $data['address']);
        $this->assertNestedData('organization_contact', $data['contact'], 'contact_data', [
            [
                'table' => 'organization_contact_telecom',
                'data' => 'telecom'
            ]
        ]);
    }


    /**
     * Test apakah user dapat memperbarui data organisasi
     */
    public function test_users_can_update_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('organization');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('organization.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['organization']['id'] = $newData['id'];
        $data['organization']['resource_id'] = $newData['resource_id'];
        $data['organization']['name'] = 'Leon';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['organization_id'] = $newData['identifier'][0]['organization_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', route('organization.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('organization', $data['organization']);
        $this->assertManyData('organization_identifier', $data['identifier']);
        $this->assertManyData('organization_telecom', $data['telecom']);
        $this->assertManyData('organization_address', $data['address']);
        $this->assertNestedData('organization_contact', $data['contact'], 'contact_data', [
            [
                'table' => 'organization_contact_telecom',
                'data' => 'telecom'
            ]
        ]);
    }
}
