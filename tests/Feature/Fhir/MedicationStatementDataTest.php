<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class MedicationStatementDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data riwayat pengobatan
     */
    public function test_users_can_view_medication_statement_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationstatement');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationstatement', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', '/api/medicationstatement/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    // /**
    //  * Test apakah user dapat membuat data riwayat pengobatan baru
    //  */
    public function test_users_can_create_new_medication_statement_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationstatement');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationstatement', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('medication_statement', $data['medicationStatement']);
        $this->assertManyData('medication_statement_note', $data['note']);
        $this->assertNestedData('medication_statement_dosage', $data['dosage'], 'dosage_data', [
            [
                'table' => 'med_state_dosage_dose_rate',
                'data' => 'doseRate'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('medication_statement_identifier', ['system' => 'http://sys-ids.kemkes.go.id/medicationstatement/' . $orgId, 'use' => 'official']);
    }


    // /**
    //  * Test apakah user dapat memperbarui data riwayat pengobatan
    //  */
    public function test_users_can_update_medication_statement_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationstatement');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationstatement', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['medicationStatement']['id'] = $newData['id'];
        $data['medicationStatement']['resource_id'] = $newData['resource_id'];
        $data['medicationStatement']['status'] = 'completed';

        $response = $this->json('PUT', '/api/medicationstatement/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('medication_statement', $data['medicationStatement']);
        $this->assertManyData('medication_statement_note', $data['note']);
        $this->assertNestedData('medication_statement_dosage', $data['dosage'], 'dosage_data', [
            [
                'table' => 'med_state_dosage_dose_rate',
                'data' => 'doseRate'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('medication_statement_identifier', ['system' => 'http://sys-ids.kemkes.go.id/medicationstatement/' . $orgId, 'use' => 'official']);
    }
}
