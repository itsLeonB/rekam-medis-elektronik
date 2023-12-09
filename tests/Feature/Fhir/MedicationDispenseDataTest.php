<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class MedicationDispenseDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'medicationdispense';

    /**
     * Test apakah user dapat menlihat data pengeluaran obat
     */
    public function test_users_can_view_medication_dispense_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['res_id' => $newData['resource_id']]));
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
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('medicationdispense.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('medication_dispense', $data['medicationDispense']);
        $this->assertManyData('medication_dispense_performer', $data['performer']);
        $this->assertNestedData('medication_dispense_dosage', $data['dosageInstruction'], 'dosageInstruction_data', [
            [
                'table' => 'med_disp_dosage_dose_rate',
                'data' => 'doseRate'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('medication_dispense_identifier', ['system' => 'http://sys-ids.kemkes.go.id/medicationdispense/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data pengeluaran obat
     */
    public function test_users_can_update_medication_dispense_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationdispense');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('medicationdispense.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['medicationDispense']['id'] = $newData['id'];
        $data['medicationDispense']['resource_id'] = $newData['resource_id'];
        $data['medicationDispense']['status'] = 'completed';
        $response = $this->json('PUT', route('medicationdispense.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('medication_dispense', $data['medicationDispense']);
        $this->assertManyData('medication_dispense_performer', $data['performer']);
        $this->assertNestedData('medication_dispense_dosage', $data['dosageInstruction'], 'dosageInstruction_data', [
            [
                'table' => 'med_disp_dosage_dose_rate',
                'data' => 'doseRate'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('medication_dispense_identifier', ['system' => 'http://sys-ids.kemkes.go.id/medicationdispense/' . $orgId, 'use' => 'official']);
    }
}
