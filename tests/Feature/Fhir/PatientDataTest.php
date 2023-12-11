<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class PatientDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'patient';

    /**
     * Test apakah user dapat menlihat data pasien
     */
    public function test_users_can_view_patient_data()
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
        $this->assertManyData('patient_name', $data['name']);
        $this->assertManyData('patient_telecom', $data['telecom']);
        $this->assertManyData('patient_address', $data['address']);
        $this->assertManyData('patient_photo', $data['photo']);
        $this->assertManyData('patient_communication', $data['communication']);
        $this->assertManyData('patient_link', $data['link']);
        $this->assertNestedData('patient_contact', $data['contact'], 'contact_data', [
            [
                'table' => 'patient_contact_telecom',
                'data' => 'telecom'
            ]
        ]);
    }
}
