<?php

namespace Tests\Unit;

use App\Models\Fhir\AllergyIntolerance;
use App\Models\Fhir\ClinicalImpression;
use App\Models\Fhir\Composition;
use App\Models\Fhir\Condition;
use App\Models\Fhir\Encounter;
use App\Models\Fhir\MedicationDispense;
use App\Models\Fhir\MedicationRequest;
use App\Models\Fhir\MedicationStatement;
use App\Models\Fhir\Observation;
use App\Models\Fhir\Patient;
use App\Models\Fhir\PatientIdentifier;
use App\Models\Fhir\PatientName;
use App\Models\Fhir\Procedure;
use App\Models\Fhir\QuestionnaireResponse;
use App\Models\Fhir\Resource;
use App\Models\Fhir\ServiceRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class RekamMedisTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;


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


    public function test_show_rekam_medis()
    {
        $patient = $this->fakeData(Patient::class, []);
        $patientSatusehatId = $patient->resource->satusehat_id;

        $encounter = $this->fakeData(Encounter::class, ['subject' => 'Patient/' . $patientSatusehatId]);
        $encounterSatusehatId = $encounter->resource->satusehat_id;

        $encCondition = Condition::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encObservation = Observation::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encProcedure = Procedure::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encMedicationRequest = MedicationRequest::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encComposition = Composition::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encAllergyIntolerance = AllergyIntolerance::factory()->create([
            'patient' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encClinicalImpression = ClinicalImpression::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encServiceRequest = ServiceRequest::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encMedicationDispense = MedicationDispense::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'context' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encMedicationStatement = MedicationStatement::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'context' => 'Encounter/' . $encounterSatusehatId
        ]);

        $encQuestionnaireResponse = QuestionnaireResponse::factory()->create([
            'subject' => 'Patient/' . $patientSatusehatId,
            'encounter' => 'Encounter/' . $encounterSatusehatId
        ]);

        $patMedicationRequest = MedicationRequest::factory()->create(['subject' => 'Patient/' . $patientSatusehatId]);
        $patComposition = Composition::factory()->create(['subject' => 'Patient/' . $patientSatusehatId]);
        $patAllergyIntolerance = AllergyIntolerance::factory()->create(['patient' => 'Patient/' . $patientSatusehatId]);
        $patMedicationDispense = MedicationDispense::factory()->create(['subject' => 'Patient/' . $patientSatusehatId]);
        $patMedicationStatement = MedicationStatement::factory()->create(['subject' => 'Patient/' . $patientSatusehatId]);
        $patQuestionnaireResponse = QuestionnaireResponse::factory()->create(['subject' => 'Patient/' . $patientSatusehatId]);

        $response = $this->get(route('rekam-medis.show', $patient->id));

        $response->assertStatus(200);

        $encComposition->identifier_value = (string)$encComposition->identifier_value;
        $encQuestionnaireResponse->identifier_value = (string)$encQuestionnaireResponse->identifier_value;
        $patComposition->identifier_value = (string)$patComposition->identifier_value;
        $patQuestionnaireResponse->identifier_value = (string)$patQuestionnaireResponse->identifier_value;

        $response->assertJsonFragment($patient->toArray());
        $response->assertJsonFragment($encounter->toArray());
        $response->assertJsonFragment($encCondition->toArray());
        $response->assertJsonFragment($encObservation->toArray());
        $response->assertJsonFragment($encProcedure->toArray());
        $response->assertJsonFragment($encMedicationRequest->toArray());
        $response->assertJsonFragment($encComposition->toArray());
        $response->assertJsonFragment($encAllergyIntolerance->toArray());
        $response->assertJsonFragment($encClinicalImpression->toArray());
        $response->assertJsonFragment($encServiceRequest->toArray());
        $response->assertJsonFragment($encMedicationDispense->toArray());
        $response->assertJsonFragment($encMedicationStatement->toArray());
        $response->assertJsonFragment($encQuestionnaireResponse->toArray());
        $response->assertJsonFragment($patMedicationRequest->toArray());
        $response->assertJsonFragment($patComposition->toArray());
        $response->assertJsonFragment($patAllergyIntolerance->toArray());
        $response->assertJsonFragment($patMedicationDispense->toArray());
        $response->assertJsonFragment($patMedicationStatement->toArray());
        $response->assertJsonFragment($patQuestionnaireResponse->toArray());
    }


    public function test_show_rekam_medis_invalid()
    {
        $response = $this->get(route('rekam-medis.show', 0));

        $response->assertStatus(404);
    }
}
