<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class OrganizationDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'Organization';

    /**
     * Test apakah user dapat menlihat data organisasi
     */
    public function test_users_can_view_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['satusehat_id' => $newData['id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data organisasi baru
     */
    public function test_users_can_create_new_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('organization', 1);
        $this->assertDatabaseCount('address', 1);
        $this->assertDatabaseCount('complex_extensions', 1);
        $this->assertDatabaseCount('extensions', 4);
        $this->assertDatabaseCount('identifiers', 1);
        $this->assertDatabaseCount('references', 1);
        $this->assertDatabaseCount('contact_points', 3);
        $this->assertDatabaseCount('codeable_concepts', 1);
        $this->assertDatabaseCount('codings', 1);
    }


    /**
     * Test apakah user dapat memperbarui data organisasi
     */
    public function test_users_can_update_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['active'] = true;
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route(self::RESOURCE_TYPE . '.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 2);
        $this->assertDatabaseCount('organization', 1);
        $this->assertDatabaseCount('address', 1);
        $this->assertDatabaseCount('complex_extensions', 1);
        $this->assertDatabaseCount('extensions', 4);
        $this->assertDatabaseCount('identifiers', 1);
        $this->assertDatabaseCount('references', 1);
        $this->assertDatabaseCount('contact_points', 3);
        $this->assertDatabaseCount('codeable_concepts', 1);
        $this->assertDatabaseCount('codings', 1);
    }
}
