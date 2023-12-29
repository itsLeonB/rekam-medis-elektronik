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
    // public function test_users_can_view_organization_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData(self::RESOURCE_TYPE);

    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
    //     $newData = json_decode($response->getContent(), true);

    //     $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['res_id' => $newData['resource_id']]));
    //     $response->assertStatus(200);
    // }


    /**
     * Test apakah user dapat membuat data organisasi baru
     */
    // public function test_users_can_create_new_organization_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData(self::RESOURCE_TYPE);

    //     $headers = ['Content-Type' => 'application/json'];
    //     $response = $this->json('POST', route('organization.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    /**
     * Test apakah user dapat memperbarui data organisasi
     */
    public function test_users_can_update_organization_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('Organization');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('organization.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['active'] = true;
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route('organization.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
