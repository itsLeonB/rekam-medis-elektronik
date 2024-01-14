<?php

namespace Tests\Unit;

use App\Http\Resources\PatientResource;
use App\Models\Fhir\Resource;
use Database\Seeders\DummyDataSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class RekamMedisTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    private function setUpTestData(bool $patientEncounterOnly = true)
    {
        $seeder = new DummyDataSeeder();

        $seeder->seedOnboarding();

        if ($patientEncounterOnly) {
            return $seeder->makeDummies(true, true);
        }

        return $seeder->makeDummies(true);
    }

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
        [$patient, $encounter] = $this->setUpTestData();

        // Make a GET request to the index endpoint
        $response = $this->get(route('rekam-medis.index'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the response contains the formatted patient data
        $this->assertFragment($response, $patient, $encounter);
    }

    public function test_index_rekam_medis_with_query_name()
    {
        [$patient, $encounter] = $this->setUpTestData();

        // Make a GET request to the index endpoint
        $response = $this->get(route('rekam-medis.index', ['name' => $patient->name()->first()->text]));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the response contains the formatted patient data
        $this->assertFragment($response, $patient, $encounter);
    }

    public function test_show_rekam_medis()
    {
        [$patient, $encounter, $conditionId, $observationId, $procedureId, $encMedReqId, $patMedReqId, $encCompId, $patCompId, $encAllergyId, $patAllergyId, $clinicId, $encServiceRequest, $encMedStateId, $patMedStateId, $encQuestionId, $patQuestionId] = $this->setUpTestData(false);

        // $encCondition = Resource::where('res_type', 'Condition')->where('satusehat_id', $conditionId)->first();
        // $encObservation = Resource::where('res_type', 'Observation')->where('satusehat_id', $observationId)->first();
        // $encProcedure = Resource::where('res_type', 'Procedure')->where('satusehat_id', $procedureId)->first();
        // $encMedicationRequest = Resource::where('res_type', 'MedicationRequest')->where('satusehat_id', $encMedReqId)->first();
        // $encComposition = Resource::where('res_type', 'Composition')->where('satusehat_id', $encCompId)->first();
        // $encAllergyIntolerance = Resource::where('res_type', 'AllergyIntolerance')->where('satusehat_id', $encAllergyId)->first();
        // $encClinicalImpression = Resource::where('res_type', 'ClinicalImpression')->where('satusehat_id', $clinicId)->first();
        // $encMedicationStatement = Resource::where('res_type', 'MedicationStatement')->where('satusehat_id', $encMedStateId)->first();
        // $encQuestionnaireResponse = Resource::where('res_type', 'QuestionnaireResponse')->where('satusehat_id', $encQuestionId)->first();
        // $patMedicationRequest = Resource::where('res_type', 'MedicationRequest')->where('satusehat_id', $patMedReqId)->first();
        // $patComposition = Resource::where('res_type', 'Composition')->where('satusehat_id', $patCompId)->first();
        // $patAllergyIntolerance = Resource::where('res_type', 'AllergyIntolerance')->where('satusehat_id', $patAllergyId)->first();
        // $patMedicationStatement = Resource::where('res_type', 'MedicationStatement')->where('satusehat_id', $patMedStateId)->first();
        // $patQuestionnaireResponse = Resource::where('res_type', 'QuestionnaireResponse')->where('satusehat_id', $patQuestionId)->first();

        $response = $this->get(route('rekam-medis.show', $patient->resource->satusehat_id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'patient' => ['resourceType', 'id', 'identifier'],
            'encounters' => [['encounter', 'conditions', 'observations']],
            'additionalData' => ['medicationRequests', 'compositions']
        ]);
    }

    // public function test_show_rekam_medis_invalid()
    // {
    //     $response = $this->get(route('rekam-medis.show', 0));

    //     $response->assertStatus(404);
    // }

    // public function test_update_data_invalid()
    // {
    //     $response = $this->get(route('rekam-medis.update', ['patient_id' => '0']));

    //     $this->assertEquals(404, $response->getStatusCode());
    // }

    // public function test_update_data()
    // {
    //     $response = $this->get(route('rekam-medis.update', ['patient_id' => '100000030009']));

    //     $this->assertEquals(200, $response->getStatusCode());
    // }
}
