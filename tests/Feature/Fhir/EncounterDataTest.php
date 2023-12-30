<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class EncounterDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'encounter';

    /**
     * Test apakah user dapat menlihat data kunjungan pasien
     */
    // public function test_users_can_view_encounter_data()
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
     * Test apakah user dapat membuat data kunjungan pasien baru
     */
    // public function test_users_can_create_new_encounter_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('Encounter');
    //     $headers = ['Content-Type' => 'application/json'];
    //     $response = $this->json('POST', route('encounter.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    /**
     * Test apakah user dapat memperbarui data kunjungan pasien
     */
    public function test_users_can_update_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('Encounter');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('encounter.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['status'] = 'planned';
        $newData['identifier'][0]['value'] = '1234567890';

        // dd($newData);

        $response = $this->json('PUT', route('encounter.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
