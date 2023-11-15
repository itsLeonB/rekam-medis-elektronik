<?php

namespace Tests\Feature;

use App\Models\MedicationDispense;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class MedicationDispenseDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data pengeluaran obat
     */
    public function test_users_can_view_medication_dispense_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationdispense');

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'MedicationDispense',
                'res_ver' => 1
            ]
        );

        $medicationDispenseData = array_merge(['resource_id' => $resource->id], $data['medication_dispense']);

        MedicationDispense::create($medicationDispenseData);

        $response = $this->json('GET', 'api/medicationdispense/P000000');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data pengeluaran obat baru
     */
    public function test_users_can_create_new_medication_dispense_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationdispense');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationdispense/create', $data, $headers);
        $response->assertStatus(201);
    }
}
