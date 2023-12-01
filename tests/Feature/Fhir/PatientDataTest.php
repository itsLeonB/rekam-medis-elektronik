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
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/patient', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', '/api/patient/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data pasien baru
     */
    public function test_users_can_create_new_patient_data()
    {
        Config::set('app.organization_id', env('organization_id'));

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
    }
}
