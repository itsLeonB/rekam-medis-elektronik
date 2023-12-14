<?php

namespace Tests\Unit;

use App\Models\Fhir\Encounter;
use App\Models\Fhir\Patient;
use App\Models\Fhir\PatientIdentifier;
use App\Models\Fhir\PatientName;
use App\Models\Fhir\Resource;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_daftar_pasien()
    {
        // Create test data
        $class = 'AMB';
        $serviceType = 124;

        $patientResource = Resource::factory()->create([
            'res_type' => 'Patient',
        ]);
        $patient = Patient::factory()->create([
            'resource_id' => $patientResource->id
        ]);
        $patientName = PatientName::factory()->create([
            'patient_id' => $patient->id
        ]);
        $patientId = PatientIdentifier::factory()->create([
            'patient_id' => $patient->id
        ]);

        $encounterResource = Resource::factory()->create([
            'res_type' => 'Encounter',
        ]);
        $encounterAttributes = [
            'resource_id' => $encounterResource->id,
            'class' => $class,
            'service_type' => $serviceType,
            'subject' => 'Patient/' . $patientResource->satusehat_id
        ];
        $encounter = Encounter::factory()->create($encounterAttributes);

        // Make the request to the controller
        $response = $this->get(route('daftar-pasien.index', ['class' => $class, 'serviceType' => $serviceType]));

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $encounter->id]);
        $response->assertJsonFragment(['text' => $patientName->text]);
        $response->assertJsonFragment(['value' => (string)$patientId->value]);
        $response->assertJsonFragment(['period_start' => $encounter->period_start]);
    }
}
