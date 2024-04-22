<?php

namespace Tests\Unit;

use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use App\Models\User;
use Database\Seeders\DummyDataSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class RekamMedisTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    private function assertFragment($response, $patient, $encounter)
    {
        $response->assertJsonFragment([
            'satusehatId' => $patient->resource->satusehat_id,
            'nik' => $patient->identifier()->where('system', config('app.identifier_systems.patient.nik'))->first()->value ?? null,
            'nik-ibu' => $patient->identifier()->where('system', config('app.identifier_systems.patient.nik-ibu'))->first()->value ?? null,
            'paspor' => $patient->identifier()->where('system', config('app.identifier_systems.patient.paspor'))->first()->value ?? null,
            'kk' => $patient->identifier()->where('system', config('app.identifier_systems.patient.kk'))->first()->value ?? null,
            'rekam-medis' => $patient->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->first()->value ?? null,
            'ihs-number' => $patient->identifier()->where('system', config('app.identifier_systems.patient.ihs-number'))->first()->value ?? null,
            'name' => $patient->name()->first()->text,
            'class' => $encounter->class->code,
            'start' => $encounter->period->start->setTimezone(config('app.timezone'))->format('Y-m-d\TH:i:sP'),
            'serviceType' => data_get($encounter, 'serviceType.coding.0.code'),
        ]);
    }

    public function test_index_rekam_medis()
    {
        $user = User::factory()->create();

        // Make a GET request to the index endpoint
        $response = $this->actingAs($user)->get(route('rekam-medis.index'));

        // Assert that the response is successful
        $response->assertStatus(200);
    }

    public function test_show_rekam_medis()
    {
        $user = User::factory()->create();

        $satusehatId = Patient::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('rekam-medis.show', $satusehatId));

        $response->assertStatus(200);
    }

    public function test_show_rekam_medis_invalid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('rekam-medis.show', 0));

        $response->assertStatus(404);
    }

    public function test_update_data_invalid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('rekam-medis.update', ['patient_id' => '0']));

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_update_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('rekam-medis.update', ['patient_id' => '100000030009']));

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_get_condition()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.condition', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_observation()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.observation', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_procedure()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.procedure', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_medication_request()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.medicationrequest', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_composition()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.composition', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_allergy_intolerance()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.allergyintolerance', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_clinical_impression()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.clinicalimpression', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_service_request()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.servicerequest', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_medication_statement()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.medicationstatement', $encounterId));

        $response->assertStatus(200);
    }

    public function test_get_questionnaire_response()
    {
        $user = User::factory()->create();

        $encounterId = Encounter::first()->resource->satusehat_id;

        $response = $this->actingAs($user)->get(route('kunjungan.questionnaireresponse', $encounterId));

        $response->assertStatus(200);
    }
}
