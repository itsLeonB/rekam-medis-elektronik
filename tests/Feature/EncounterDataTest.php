<?php

namespace Tests\Feature;

use App\Models\Encounter;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class EncounterDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data kunjungan pasien
     */
    public function test_users_can_view_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('encounter');

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'Encounter',
                'res_ver' => 1
            ]
        );

        $encounterData = array_merge(['resource_id' => $resource->id], $data['encounter']);

        Encounter::create($encounterData);

        $response = $this->json('GET', 'api/encounter/000001');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data kunjungan pasien baru
     */
    public function test_users_can_create_new_encounter_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('encounter');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/encounter/create', $data, $headers);
        $response->assertStatus(201);
    }
}
