<?php

namespace Tests\Unit;

use App\Models\Fhir\Encounter;
use App\Models\Fhir\Patient;
use App\Models\Fhir\PatientIdentifier;
use App\Models\Fhir\PatientName;
use App\Models\Fhir\Resource;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RekamMedisTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index_rekam_medis()
    {
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
        $encounter = Encounter::factory()->create([
            'resource_id' => $encounterResource->id,
            'subject' => 'Patient/' . $patientResource->satusehat_id
        ]);

        // Make the request to the controller
        $response = $this->get(route('rekam-medis.index'));

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $patient->id]);
        $response->assertJsonFragment(['text' => $patientName->text]);
        $response->assertJsonFragment(['value' => (string)$patientId->value]);
        $response->assertJsonFragment(['class' => $encounter->class]);
        $response->assertJsonFragment(['period_start' => date_format($encounter->period_start, 'Y-m-d H:i:s')]);
    }
}
