<?php

namespace Tests\Feature;

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

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationdispense/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/medicationdispense/' . $newData['resource_id']);
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

        $this->assertMainData('medication_dispense', $data['medicationDispense']);
        $this->assertManyData('medication_dispense_identifier', $data['identifier']);
        $this->assertManyData('medication_dispense_part_of', $data['partOf']);
        $this->assertManyData('medication_dispense_authorizing_prescription', $data['authorizingPrescription']);
        $this->assertNestedData('medication_dispense_dosage', $data['dosage'], 'dosage_data', [
            [
                'table' => 'med_disp_dosage_add_instruct',
                'data' => 'additionalInstruction'
            ],
            [
                'table' => 'med_disp_dosage_dose_rate',
                'data' => 'doseRate'
            ]
        ]);
        $this->assertNestedData('medication_dispense_substitution', $data['substitution'], 'substitution_data', [
            [
                'table' => 'med_disp_subs_reason',
                'data' => 'reason'
            ],
            [
                'table' => 'med_disp_subs_responsible_party',
                'data' => 'responsibleParty'
            ]
        ]);
    }


    /**
     * Test apakah user dapat memperbarui data pengeluaran obat
     */
    public function test_users_can_update_medication_dispense_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medicationdispense');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/medicationdispense/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['medicationDispense']['id'] = $newData['id'];
        $data['medicationDispense']['resource_id'] = $newData['resource_id'];
        $data['medicationDispense']['status'] = 'completed';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['dispense_id'] = $newData['identifier'][0]['dispense_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/medicationdispense/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('medication_dispense', $data['medicationDispense']);
        $this->assertManyData('medication_dispense_identifier', $data['identifier']);
        $this->assertManyData('medication_dispense_part_of', $data['partOf']);
        $this->assertManyData('medication_dispense_authorizing_prescription', $data['authorizingPrescription']);
        $this->assertNestedData('medication_dispense_dosage', $data['dosage'], 'dosage_data', [
            [
                'table' => 'med_disp_dosage_add_instruct',
                'data' => 'additionalInstruction'
            ],
            [
                'table' => 'med_disp_dosage_dose_rate',
                'data' => 'doseRate'
            ]
        ]);
        $this->assertNestedData('medication_dispense_substitution', $data['substitution'], 'substitution_data', [
            [
                'table' => 'med_disp_subs_reason',
                'data' => 'reason'
            ],
            [
                'table' => 'med_disp_subs_responsible_party',
                'data' => 'responsibleParty'
            ]
        ]);
    }
}
