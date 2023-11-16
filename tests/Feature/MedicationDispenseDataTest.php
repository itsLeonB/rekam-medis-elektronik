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

        $this->assertMainData('medication_dispense', $data['medication_dispense']);
        $this->assertManyData('medication_dispense_identifier', $data['identifier']);
        $this->assertManyData('medication_dispense_part_of', $data['part_of']);
        $this->assertManyData('medication_dispense_authorizing_prescription', $data['authorizing_prescription']);
        $this->assertNestedData('medication_dispense_dosage', $data['dosage'], 'dosage_data', [
            [
                'table' => 'med_disp_dosage_add_instruct',
                'data' => 'additional_instruction'
            ],
            [
                'table' => 'med_disp_dosage_dose_rate',
                'data' => 'dose_rate'
            ]
        ]);
        $this->assertNestedData('medication_dispense_substitution', $data['substitution'], 'substitution_data', [
            [
                'table' => 'med_disp_subs_reason',
                'data' => 'reason'
            ],
            [
                'table' => 'med_disp_subs_responsible_party',
                'data' => 'responsible_party'
            ]
        ]);
    }
}
