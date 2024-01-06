<?php

namespace Database\Seeders;

use App\Models\Fhir\Datatypes\CodeableConcept;
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
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encounters = Encounter::factory()->count(4)->create();

        Period::factory()->create([
            'start' => Carbon::today(),
            'end' => Carbon::today()->addHours(2),
            'periodable_id' => $encounters[0]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => Carbon::today(),
            'end' => Carbon::today()->addHours(4),
            'periodable_id' => $encounters[1]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => Carbon::yesterday(),
            'end' => Carbon::yesterday()->addHours(2),
            'periodable_id' => $encounters[2]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => Carbon::tomorrow(),
            'end' => Carbon::tomorrow()->addHours(2),
            'periodable_id' => $encounters[3]->id,
            'periodable_type' => 'Encounter',
        ]);

        $patCount = fake()->numberBetween(1, 10);

        // Create a new patient resource
        Resource::factory()->count($patCount)->create([
            'res_type' => 'Patient',
            'created_at' => Carbon::now()->startOfMonth(),
        ]);

        // Create another patient resource from last month
        Resource::factory()->create([
            'res_type' => 'Patient',
            'created_at' => Carbon::now()->subMonth()->startOfMonth(),
        ]);

        $patCount = fake()->numberBetween(1, 10);

        // Create a new patient resource
        Patient::factory()->count($patCount)->create();

        $encounters = Encounter::factory()->count(5)->create();

        Period::factory()->create([
            'start' => now()->subMonths(10),
            'end' => now()->subMonths(10)->addHours(2),
            'periodable_id' => $encounters[0]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(8),
            'end' => now()->subMonths(8)->addHours(2),
            'periodable_id' => $encounters[1]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(6),
            'end' => now()->subMonths(6)->addHours(2),
            'periodable_id' => $encounters[2]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(4),
            'end' => now()->subMonths(4)->addHours(2),
            'periodable_id' => $encounters[3]->id,
            'periodable_type' => 'Encounter',
        ]);

        Period::factory()->create([
            'start' => now()->subMonths(2),
            'end' => now()->subMonths(2)->addHours(2),
            'periodable_id' => $encounters[4]->id,
            'periodable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'AMB',
            'attr_type' => 'class',
            'codeable_id' => $encounters[0]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'AMB',
            'attr_type' => 'class',
            'codeable_id' => $encounters[1]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'EMER',
            'attr_type' => 'class',
            'codeable_id' => $encounters[2]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'EMER',
            'attr_type' => 'class',
            'codeable_id' => $encounters[3]->id,
            'codeable_type' => 'Encounter',
        ]);

        Coding::factory()->create([
            'code' => 'IMP',
            'attr_type' => 'class',
            'codeable_id' => $encounters[4]->id,
            'codeable_type' => 'Encounter',
        ]);

        $balita = fake()->numberBetween(1, 10);
        $kanak = fake()->numberBetween(1, 10);
        $remaja = fake()->numberBetween(1, 10);
        $dewasa = fake()->numberBetween(1, 10);
        $lansia = fake()->numberBetween(1, 10);
        $manula = fake()->numberBetween(1, 10);

        Patient::factory()->count($balita)->create([
            'birth_date' => now()->subYears(1),
        ]);
        Patient::factory()->count($kanak)->create([
            'birth_date' => now()->subYears(6),
        ]);
        Patient::factory()->count($remaja)->create([
            'birth_date' => now()->subYears(12),
        ]);
        Patient::factory()->count($dewasa)->create([
            'birth_date' => now()->subYears(26),
        ]);
        Patient::factory()->count($lansia)->create([
            'birth_date' => now()->subYears(46),
        ]);
        Patient::factory()->count($manula)->create([
            'birth_date' => now()->subYears(66),
        ]);

        $classes = ['AMB', 'IMP', 'EMER'];
        $serviceTypes = [124, 177, 186, 88, 168, 218, 221, 286, 263, 189, 221, 124, 286];

        $class = $classes[array_rand($classes)];
        $serviceType = $serviceTypes[array_rand($serviceTypes)];

        $patient = Patient::factory()->create();

        HumanName::factory()->create([
            'human_nameable_id' => $patient->id,
            'human_nameable_type' => 'Patient',
        ]);

        Identifier::factory()->create([
            'identifiable_id' => $patient->id,
            'identifiable_type' => 'Patient',
            'system' => 'rme',
        ]);

        $encounter = Encounter::factory()->create();

        Coding::factory()->create([
            'code' => $class,
            'codeable_id' => $encounter->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);

        $serviceTypeRelation = CodeableConcept::factory()->create([
            'codeable_id' => $encounter->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'serviceType'
        ]);

        Coding::factory()->create([
            'code' => $serviceType,
            'codeable_id' => $serviceTypeRelation->id,
            'codeable_type' => 'CodeableConcept',
            'attr_type' => 'coding'
        ]);

        Period::factory()->create([
            'periodable_id' => $encounter->id,
            'periodable_type' => 'Encounter',
        ]);

        Reference::factory()->create([
            'reference' => 'Patient/' . $patient->resource->satusehat_id,
            'referenceable_id' => $encounter->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);

        $patient1 = Patient::factory()->create();
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
            'codeable_id' => $encounter1->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);
        Period::factory()->create([
            'periodable_id' => $encounter1->id,
            'periodable_type' => 'Encounter',
            'attr_type' => 'period'
        ]);

        $patient2 = Patient::factory()->create();
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
            'code' => $classes[array_rand($classes)],
            'codeable_id' => $encounter2->id,
            'codeable_type' => 'Encounter',
            'attr_type' => 'class'
        ]);
        Period::factory()->create([
            'periodable_id' => $encounter2->id,
            'periodable_type' => 'Encounter',
            'attr_type' => 'period'
        ]);

        $patient = Patient::factory()->create();
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
    }
}
