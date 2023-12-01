<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class PatientDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data pasien
     */
    public function test_users_can_view_patient_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('patient.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route('resource.show', ['res_type' => 'patient', 'res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data pasien baru
     */
    public function test_users_can_create_new_patient_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', '/api/patient', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('patient', $data['patient']);
        $this->assertManyData('patient_identifier', $data['identifier']);
        $this->assertManyData('patient_telecom', $data['telecom']);
        $this->assertManyData('patient_address', $data['address']);
        $this->assertNestedData('patient_contact', $data['contact'], 'contact_data', [
            [
                'table' => 'patient_contact_telecom',
                'data' => 'telecom'
            ]
        ]);
    }
}
