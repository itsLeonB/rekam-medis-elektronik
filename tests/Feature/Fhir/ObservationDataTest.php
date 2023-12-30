<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ObservationDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'observation';

    /**
     * Test apakah user dapat menlihat data observasi
     */
    // public function test_users_can_view_observation_data()
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
     * Test apakah user dapat membuat data observasi baru
     */
    // public function test_users_can_create_new_observation_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('Observation');
    //     $headers = ['Content-Type' => 'application/json'];
    //     $response = $this->json('POST', route('observation.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    /**
     * Test apakah user dapat memperbarui data observasi
     */
    public function test_users_can_update_observation_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('Observation');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('observation.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['effectiveDateTime'] = '2022-07-15';
        $newData['encounter']['display'] = 'Pemeriksaan update';

        $response = $this->json('PUT', route('observation.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
