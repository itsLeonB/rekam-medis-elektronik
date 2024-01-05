<?php

namespace Tests\Unit;

use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
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
        // Create some test patients
        $patient1 = Patient::factory()->create();
        $identifier1 = Identifier::factory()->create([
            'identifiable_id' => $patient1->id,
            'identifiable_type' => 'Patient',
            'attr_type' => 'identifier'
        ]);
        $name1 = HumanName::factory()->create([
            'human_nameable_id' => $patient1->id,
            'human_nameable_type' => 'Patient',
            'attr_type' => 'name'
        ]);

        $patient1->identifier()->save($identifier1);
        $patient1->name()->save($name1);

        $encounter1 = Encounter::factory()->create();
        $subject1 = Reference::factory()->create([
            'reference' => 'Patient/' . $patient1->resource->satusehat_id,
            'referenceable_id' => $encounter1->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);
        $class1 = Coding::factory()->create([
            'codeable_id' => $encounter1->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);
        $period1 = Period::factory()->create([
            'periodable_id' => $encounter1->id,
            'periodable_type' => 'Encounter',
            'attr_type' => 'period'
        ]);

        $encounter1->subject()->save($subject1);
        $encounter1->class()->save($class1);
        $encounter1->period()->save($period1);

        $patient2 = Patient::factory()->create();
        $identifier2 = Identifier::factory()->create([
            'identifiable_id' => $patient2->id,
            'identifiable_type' => 'Patient',
            'attr_type' => 'identifier'
        ]);
        $name2 = HumanName::factory()->create([
            'human_nameable_id' => $patient2->id,
            'human_nameable_type' => 'Patient',
            'attr_type' => 'name'
        ]);

        $patient2->identifier()->save($identifier2);
        $patient2->name()->save($name2);

        $encounter2 = Encounter::factory()->create();
        $subject2 = Reference::factory()->create([
            'reference' => 'Patient/' . $patient2->resource->satusehat_id,
            'referenceable_id' => $encounter2->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);
        $class2 = Coding::factory()->create([
            'codeable_id' => $encounter2->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);
        $period2 = Period::factory()->create([
            'periodable_id' => $encounter2->id,
            'periodable_type' => 'Encounter',
            'attr_type' => 'period'
        ]);

        $encounter2->subject()->save($subject2);
        $encounter2->class()->save($class2);
        $encounter2->period()->save($period2);

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
        $patient = Patient::factory()->create();
        $patientSatusehatId = $patient->resource->satusehat_id;

        $encounter = Encounter::factory()->create();
        $encSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encounter->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);
        $encounter->subject()->save($encSubject);
        $encounterSatusehatId = $encounter->resource->satusehat_id;

        $encCondition = Condition::factory()->create();
        $encCondSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encCondition->id,
            'referenceable_type' => 'Condition',
            'attr_type' => 'subject'
        ]);
        $encCondEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encCondition->id,
            'referenceable_type' => 'Condition',
            'attr_type' => 'encounter'
        ]);
        $encCondition->subject()->save($encCondSubject);
        $encCondition->encounter()->save($encCondEncounter);

        $encObservation = Observation::factory()->create();
        $encObsSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encObservation->id,
            'referenceable_type' => 'Observation',
            'attr_type' => 'subject'
        ]);
        $encObsEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encObservation->id,
            'referenceable_type' => 'Observation',
            'attr_type' => 'encounter'
        ]);
        $encObservation->subject()->save($encObsSubject);
        $encObservation->encounter()->save($encObsEncounter);

        $encProcedure = Procedure::factory()->create();
        $encProcSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encProcedure->id,
            'referenceable_type' => 'Procedure',
            'attr_type' => 'subject'
        ]);
        $encProcEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encProcedure->id,
            'referenceable_type' => 'Procedure',
            'attr_type' => 'encounter'
        ]);
        $encProcedure->subject()->save($encProcSubject);
        $encProcedure->encounter()->save($encProcEncounter);

        $encMedicationRequest = MedicationRequest::factory()->create();
        $encMedReqSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encMedicationRequest->id,
            'referenceable_type' => 'MedicationRequest',
            'attr_type' => 'subject'
        ]);
        $encMedReqEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encMedicationRequest->id,
            'referenceable_type' => 'MedicationRequest',
            'attr_type' => 'encounter'
        ]);
        $encMedicationRequest->subject()->save($encMedReqSubject);
        $encMedicationRequest->encounter()->save($encMedReqEncounter);

        $encComposition = Composition::factory()->create();
        $encCompSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encComposition->id,
            'referenceable_type' => 'Composition',
            'attr_type' => 'subject'
        ]);
        $encCompEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encComposition->id,
            'referenceable_type' => 'Composition',
            'attr_type' => 'encounter'
        ]);
        $encComposition->subject()->save($encCompSubject);
        $encComposition->encounter()->save($encCompEncounter);

        $encAllergyIntolerance = AllergyIntolerance::factory()->create();
        $encAllergySubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encAllergyIntolerance->id,
            'referenceable_type' => 'AllergyIntolerance',
            'attr_type' => 'patient'
        ]);
        $encAllergyEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encAllergyIntolerance->id,
            'referenceable_type' => 'AllergyIntolerance',
            'attr_type' => 'encounter'
        ]);
        $encAllergyIntolerance->patient()->save($encAllergySubject);
        $encAllergyIntolerance->encounter()->save($encAllergyEncounter);

        $encClinicalImpression = ClinicalImpression::factory()->create();
        $encClinImpSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encClinicalImpression->id,
            'referenceable_type' => 'ClinicalImpression',
            'attr_type' => 'subject'
        ]);
        $encClinImpEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encClinicalImpression->id,
            'referenceable_type' => 'ClinicalImpression',
            'attr_type' => 'encounter'
        ]);
        $encClinicalImpression->subject()->save($encClinImpSubject);
        $encClinicalImpression->encounter()->save($encClinImpEncounter);

        $encServiceRequest = ServiceRequest::factory()->create();
        $encServReqSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encServiceRequest->id,
            'referenceable_type' => 'ServiceRequest',
            'attr_type' => 'subject'
        ]);
        $encServReqEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encServiceRequest->id,
            'referenceable_type' => 'ServiceRequest',
            'attr_type' => 'encounter'
        ]);
        $encServiceRequest->subject()->save($encServReqSubject);
        $encServiceRequest->encounter()->save($encServReqEncounter);

        $encMedicationStatement = MedicationStatement::factory()->create();
        $encMedStatSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encMedicationStatement->id,
            'referenceable_type' => 'MedicationStatement',
            'attr_type' => 'subject'
        ]);
        $encMedStatContext = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encMedicationStatement->id,
            'referenceable_type' => 'MedicationStatement',
            'attr_type' => 'context'
        ]);
        $encMedicationStatement->subject()->save($encMedStatSubject);
        $encMedicationStatement->context()->save($encMedStatContext);

        $encQuestionnaireResponse = QuestionnaireResponse::factory()->create();
        $encQuestRespSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $encQuestionnaireResponse->id,
            'referenceable_type' => 'QuestionnaireResponse',
            'attr_type' => 'subject'
        ]);
        $encQuestRespEncounter = Reference::factory()->create([
            'reference' => 'Encounter/' . $encounterSatusehatId,
            'referenceable_id' => $encQuestionnaireResponse->id,
            'referenceable_type' => 'QuestionnaireResponse',
            'attr_type' => 'encounter'
        ]);
        $encQuestionnaireResponse->subject()->save($encQuestRespSubject);
        $encQuestionnaireResponse->encounter()->save($encQuestRespEncounter);

        $patMedicationRequest = MedicationRequest::factory()->create();
        $patMedReqSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patMedicationRequest->id,
            'referenceable_type' => 'MedicationRequest',
            'attr_type' => 'subject'
        ]);
        $patMedicationRequest->subject()->save($patMedReqSubject);

        $patComposition = Composition::factory()->create();
        $patCompSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patComposition->id,
            'referenceable_type' => 'Composition',
            'attr_type' => 'subject'
        ]);
        $patComposition->subject()->save($patCompSubject);

        $patAllergyIntolerance = AllergyIntolerance::factory()->create();
        $patAllergySubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patAllergyIntolerance->id,
            'referenceable_type' => 'AllergyIntolerance',
            'attr_type' => 'patient'
        ]);
        $patAllergyIntolerance->patient()->save($patAllergySubject);

        $patMedicationStatement = MedicationStatement::factory()->create();
        $patMedStatSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patMedicationStatement->id,
            'referenceable_type' => 'MedicationStatement',
            'attr_type' => 'subject'
        ]);
        $patMedicationStatement->subject()->save($patMedStatSubject);

        $patQuestionnaireResponse = QuestionnaireResponse::factory()->create();
        $patQuestRespSubject = Reference::factory()->create([
            'reference' => 'Patient/' . $patientSatusehatId,
            'referenceable_id' => $patQuestionnaireResponse->id,
            'referenceable_type' => 'QuestionnaireResponse',
            'attr_type' => 'subject'
        ]);
        $patQuestionnaireResponse->subject()->save($patQuestRespSubject);

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
