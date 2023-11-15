<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('patient');

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'Patient',
                'res_ver' => 1
            ]
        );

        $patientData = array_merge(['resource_id' => $resource->id], $data['patient']);

        Patient::create($patientData);

        $response = $this->json('GET', 'api/patient/P000000');
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

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/patient/create', $data, $headers);
        $response->assertStatus(201);
    }
}
