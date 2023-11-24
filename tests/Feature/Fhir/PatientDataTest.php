<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class PatientDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data pasien
     */
    public function test_users_can_view_patient_data()
    {
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/patient', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/patient/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data pasien baru
     */
    public function test_users_can_create_new_patient_data()
    {
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');

        $headers = [
            'Content-Type' => 'application/json'
        ];
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
        $this->assertManyData('general_practitioner', $data['generalPractitioner']);
    }


    /**
     * Test apakah user dapat memperbarui data pasien
     */
    public function test_users_can_update_patient_data()
    {
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/patient', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['patient']['id'] = $newData['id'];
        $data['patient']['resource_id'] = $newData['resource_id'];
        $data['patient']['name'] = 'Leon';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['patient_id'] = $newData['identifier'][0]['patient_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/patient/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

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
        $this->assertManyData('general_practitioner', $data['generalPractitioner']);
    }
}
