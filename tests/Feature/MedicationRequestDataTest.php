<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class MedicationRequestDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data peresepan obat
     */
    public function test_users_can_view_medication_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationrequest');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationrequest/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/medicationrequest/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    // /**
    //  * Test apakah user dapat membuat data peresepan obat baru
    //  */
    // public function test_users_can_create_new_medication_request_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('medicationrequest');
    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', '/api/medicationrequest/create', $data, $headers);
    //     $response->assertStatus(201);

    //     $this->assertMainData('medication_request', $data['medicationRequest']);
    //     $this->assertManyData('medication_request_identifier', $data['identifier']);
    //     $this->assertManyData('medication_request_category', $data['category']);
    //     $this->assertManyData('medication_request_reason', $data['reason']);
    //     $this->assertManyData('medication_request_based_on', $data['basedOn']);
    //     $this->assertManyData('medication_request_insurance', $data['insurance']);
    //     $this->assertManyData('medication_request_note', $data['note']);
    //     $this->assertNestedData('medication_request_dosage', $data['dosage'], 'dosage_data', [
    //         [
    //             'table' => 'med_req_dosage_additional_instruction',
    //             'data' => 'additionalInstruction'
    //         ],
    //         [
    //             'table' => 'med_req_dosage_dose_rate',
    //             'data' => 'doseRate'
    //         ]
    //     ]);
    // }


    // /**
    //  * Test apakah user dapat memperbarui data peresepan obat
    //  */
    // public function test_users_can_update_medication_request_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('medicationrequest');
    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', '/api/medicationrequest/create', $data, $headers);
    //     $newData = json_decode($response->getContent(), true);

    //     $data['medicationRequest']['id'] = $newData['id'];
    //     $data['medicationRequest']['resource_id'] = $newData['resource_id'];
    //     $data['medicationRequest']['priority'] = 'stat';
    //     $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
    //     $data['identifier'][0]['med_req_id'] = $newData['identifier'][0]['med_req_id'];
    //     $data['identifier'][0]['value'] = "5234341";

    //     $data['identifier'][] = [
    //         'system' => 'http://loinc.org',
    //         'use' => 'official',
    //         'value' => '1234567890'
    //     ];

    //     $response = $this->json('PUT', '/api/medicationrequest/' . $newData['resource_id'], $data, $headers);
    //     $response->assertStatus(200);

    //     $this->assertMainData('medication_request', $data['medicationRequest']);
    //     $this->assertManyData('medication_request_identifier', $data['identifier']);
    //     $this->assertManyData('medication_request_category', $data['category']);
    //     $this->assertManyData('medication_request_reason', $data['reason']);
    //     $this->assertManyData('medication_request_based_on', $data['basedOn']);
    //     $this->assertManyData('medication_request_insurance', $data['insurance']);
    //     $this->assertManyData('medication_request_note', $data['note']);
    //     $this->assertNestedData('medication_request_dosage', $data['dosage'], 'dosage_data', [
    //         [
    //             'table' => 'med_req_dosage_additional_instruction',
    //             'data' => 'additionalInstruction'
    //         ],
    //         [
    //             'table' => 'med_req_dosage_dose_rate',
    //             'data' => 'doseRate'
    //         ]
    //     ]);
    // }
}
