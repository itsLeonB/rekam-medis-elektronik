<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class SpecimenDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data spesimen pasien
     */
    public function test_users_can_view_specimen_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('specimen');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/specimen', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', '/api/specimen/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data spesimen pasien baru
     */
    public function test_users_can_create_new_specimen_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('specimen');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/specimen', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('specimen', $data['specimen']);
        $this->assertManyData('specimen_parent', $data['parent']);
        $this->assertManyData('specimen_request', $data['request']);
        $this->assertManyData('specimen_processing', $data['processing']);
        $this->assertNestedData('specimen_container', $data['container'], 'container_data');
        $this->assertManyData('specimen_note', $data['note']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('specimen_identifier', ['system' => 'http://sys-ids.kemkes.go.id/specimen/' . $orgId, 'use' => 'official']);
        $this->assertDatabaseHas('specimen_container_identifier', ['system' => 'http://sys-ids.kemkes.go.id/container/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data spesimen pasien
     */
    public function test_users_can_update_specimen_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('specimen');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/specimen', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['specimen']['id'] = $newData['id'];
        $data['specimen']['resource_id'] = $newData['resource_id'];
        $data['specimen']['status'] = 'unavailable';
        $response = $this->json('PUT', '/api/specimen/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('specimen', $data['specimen']);
        $this->assertManyData('specimen_parent', $data['parent']);
        $this->assertManyData('specimen_request', $data['request']);
        $this->assertManyData('specimen_processing', $data['processing']);
        $this->assertNestedData('specimen_container', $data['container'], 'container_data');
        $this->assertManyData('specimen_note', $data['note']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('specimen_identifier', ['system' => 'http://sys-ids.kemkes.go.id/specimen/' . $orgId, 'use' => 'official']);
        $this->assertDatabaseHas('specimen_container_identifier', ['system' => 'http://sys-ids.kemkes.go.id/container/' . $orgId, 'use' => 'official']);
    }
}
