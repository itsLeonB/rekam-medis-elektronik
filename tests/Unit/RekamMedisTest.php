<?php

namespace Tests\Unit;

use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\AllergyIntolerance;
use App\Models\Fhir\Resources\ClinicalImpression;
use App\Models\Fhir\Resources\Composition;
use App\Models\Fhir\Resources\Condition;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\MedicationRequest;
use App\Models\Fhir\Resources\MedicationStatement;
use App\Models\Fhir\Resources\Observation;
use App\Models\Fhir\Resources\Patient;
use App\Models\Fhir\Resources\Procedure;
use App\Models\Fhir\Resources\QuestionnaireResponse;
use App\Models\Fhir\Resources\ServiceRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class RekamMedisTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    public function test_index_rekam_medis()
    {
        $classes = ['AMB', 'IMP', 'EMER'];
        // Create some test patients
        $patient1 = Patient::factory()->for(Resource::factory()->create(['res_type' => 'Patient']))->create();
        Identifier::factory()->create([
            'identifiable_id' => $patient1->id,
            'identifiable_type' => 'Patient',
            'attr_type' => 'identifier'
        ]);
        HumanName::factory()->create([
            'human_nameable_id' => $patient1->id,
            'human_nameable_type' => 'Patient',
            'attr_type' => 'name'
        ]);

        $encounter1 = Encounter::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patient1->resource->satusehat_id,
            'referenceable_id' => $encounter1->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);
        Coding::factory()->create([
            'code' => fake()->randomElement($classes),
            'codeable_id' => $encounter1->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);
        Period::factory()->create([
            'periodable_id' => $encounter1->id,
            'periodable_type' => 'Encounter',
            'attr_type' => 'period'
        ]);

        $patient2 = Patient::factory()->for(Resource::factory()->create(['res_type' => 'Patient']))->create();
        Identifier::factory()->create([
            'identifiable_id' => $patient2->id,
            'identifiable_type' => 'Patient',
            'attr_type' => 'identifier'
        ]);
        HumanName::factory()->create([
            'human_nameable_id' => $patient2->id,
            'human_nameable_type' => 'Patient',
            'attr_type' => 'name'
        ]);

        $encounter2 = Encounter::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patient2->resource->satusehat_id,
            'referenceable_id' => $encounter2->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);
        Coding::factory()->create([
            'code' => fake()->randomElement($classes),
            'codeable_id' => $encounter2->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);
        Period::factory()->create([
            'periodable_id' => $encounter2->id,
            'periodable_type' => 'Encounter',
            'attr_type' => 'period'
        ]);

        // Make a GET request to the index endpoint
        $response = $this->get(route('rekam-medis.index'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the response contains the formatted patient data
        $response->assertJson([
            [
                'satusehatId' => $patient1->resource->satusehat_id,
                'identifier' => $patient1->identifier()->where('system', 'rme')->first()->value,
                'name' => $patient1->name()->first()->text,
                'class' => $encounter1->class->code,
                'start' => $encounter1->period->start->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP'),
            ],
            [
                'satusehatId' => $patient2->resource->satusehat_id,
                'identifier' => $patient2->identifier()->where('system', 'rme')->first()->value,
                'name' => $patient2->name()->first()->text,
                'class' => $encounter2->class->code,
                'start' => $encounter2->period->start->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:sP'),
            ],
        ]);
    }

    public function test_show_rekam_medis()
    {
        $patient = Patient::factory()->for(Resource::factory()->create(['res_type' => 'Patient']))->create();
        $patientSatusehatId = $patient->resource->satusehat_id;

        $encounter = Encounter::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encounter->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);
        $encounterSatusehatId = $encounter->resource->satusehat_id;

        $encCondition = Condition::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encCondition->id,
            'referenceable_type' => 'Condition',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encCondition->id,
            'referenceable_type' => 'Condition',
            'attr_type' => 'encounter'
        ]);

        $encObservation = Observation::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encObservation->id,
            'referenceable_type' => 'Observation',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encObservation->id,
            'referenceable_type' => 'Observation',
            'attr_type' => 'encounter'
        ]);

        $encProcedure = Procedure::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encProcedure->id,
            'referenceable_type' => 'Procedure',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encProcedure->id,
            'referenceable_type' => 'Procedure',
            'attr_type' => 'encounter'
        ]);

        $encMedicationRequest = MedicationRequest::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encMedicationRequest->id,
            'referenceable_type' => 'MedicationRequest',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encMedicationRequest->id,
            'referenceable_type' => 'MedicationRequest',
            'attr_type' => 'encounter'
        ]);

        $encComposition = Composition::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encComposition->id,
            'referenceable_type' => 'Composition',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encComposition->id,
            'referenceable_type' => 'Composition',
            'attr_type' => 'encounter'
        ]);

        $encAllergyIntolerance = AllergyIntolerance::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encAllergyIntolerance->id,
            'referenceable_type' => 'AllergyIntolerance',
            'attr_type' => 'patient'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encAllergyIntolerance->id,
            'referenceable_type' => 'AllergyIntolerance',
            'attr_type' => 'encounter'
        ]);

        $encClinicalImpression = ClinicalImpression::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encClinicalImpression->id,
            'referenceable_type' => 'ClinicalImpression',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encClinicalImpression->id,
            'referenceable_type' => 'ClinicalImpression',
            'attr_type' => 'encounter'
        ]);

        $encServiceRequest = ServiceRequest::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encServiceRequest->id,
            'referenceable_type' => 'ServiceRequest',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encServiceRequest->id,
            'referenceable_type' => 'ServiceRequest',
            'attr_type' => 'encounter'
        ]);

        $encMedicationStatement = MedicationStatement::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encMedicationStatement->id,
            'referenceable_type' => 'MedicationStatement',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encMedicationStatement->id,
            'referenceable_type' => 'MedicationStatement',
            'attr_type' => 'context'
        ]);

        $encQuestionnaireResponse = QuestionnaireResponse::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encQuestionnaireResponse->id,
            'referenceable_type' => 'QuestionnaireResponse',
            'attr_type' => 'subject'
        ]);
        Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encQuestionnaireResponse->id,
            'referenceable_type' => 'QuestionnaireResponse',
            'attr_type' => 'encounter'
        ]);

        $patMedicationRequest = MedicationRequest::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patMedicationRequest->id,
            'referenceable_type' => 'MedicationRequest',
            'attr_type' => 'subject'
        ]);

        $patComposition = Composition::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patComposition->id,
            'referenceable_type' => 'Composition',
            'attr_type' => 'subject'
        ]);

        $patAllergyIntolerance = AllergyIntolerance::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patAllergyIntolerance->id,
            'referenceable_type' => 'AllergyIntolerance',
            'attr_type' => 'patient'
        ]);

        $patMedicationStatement = MedicationStatement::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patMedicationStatement->id,
            'referenceable_type' => 'MedicationStatement',
            'attr_type' => 'subject'
        ]);

        $patQuestionnaireResponse = QuestionnaireResponse::factory()->create();
        Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patQuestionnaireResponse->id,
            'referenceable_type' => 'QuestionnaireResponse',
            'attr_type' => 'subject'
        ]);

        $response = $this->get(route('rekam-medis.show', $patient->id));

        $response->assertStatus(200);
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
        $response->assertJsonFragment($encMedicationStatement->toArray());
        $response->assertJsonFragment($encQuestionnaireResponse->toArray());
        $response->assertJsonFragment($patMedicationRequest->toArray());
        $response->assertJsonFragment($patComposition->toArray());
        $response->assertJsonFragment($patAllergyIntolerance->toArray());
        $response->assertJsonFragment($patMedicationStatement->toArray());
        $response->assertJsonFragment($patQuestionnaireResponse->toArray());
    }

    public function test_show_rekam_medis_invalid()
    {
        $response = $this->get(route('rekam-medis.show', 0));

        $response->assertStatus(404);
    }

    public function test_update_data_invalid()
    {
        $response = $this->get(route('rekam-medis.update', ['patient_id' => '0']));

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_update_data()
    {
        $response = $this->get(route('rekam-medis.update', ['patient_id' => '100000030009']));

        $this->assertEquals(200, $response->getStatusCode());
    }
}
