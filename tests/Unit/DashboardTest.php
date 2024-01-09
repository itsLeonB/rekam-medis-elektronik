<?php

namespace Tests\Unit;

use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resource;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutModelEvents;

    public function test_get_daftar_pasien()
    {
        // Create test data
        $classes = ['AMB', 'IMP', 'EMER'];
        $serviceTypes = [124, 177, 186, 88, 168, 218, 221, 286, 263, 189, 221, 124, 286];
        $class = fake()->randomElement($classes);
        $serviceType = fake()->randomElement($serviceTypes);

        $patient = Patient::factory()->for(Resource::factory()->create(['res_type' => 'Patient']))->create();

        $patientName = HumanName::factory()->create([
            'human_nameable_id' => $patient->id,
            'human_nameable_type' => 'Patient',
        ]);

        Identifier::factory()->create([
            'identifiable_id' => $patient->id,
            'identifiable_type' => 'Patient',
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

        $encounterPeriod = Period::factory()->create([
            'periodable_id' => $encounter->id,
            'periodable_type' => 'Encounter',
        ]);

        Reference::factory()->create([
            'reference' => 'Patient/' . $patient->resource->satusehat_id,
            'referenceable_id' => $encounter->id,
            'referenceable_type' => 'Encounter',
            'attr_type' => 'subject'
        ]);

        // Make the request to the controller
        $response = $this->get(route('daftar-pasien.index', ['class' => $class, 'serviceType' => $serviceType]));

        // Assert the response
        $response->assertStatus(200);
        $response->assertJsonFragment(['encounter_satusehat_id' => $encounter->resource->satusehat_id]);
        $response->assertJsonFragment(['patient_name' => $patientName->text]);
        $response->assertJsonFragment(['patient_identifier' => $patient->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->first()->value]);
        $response->assertJsonFragment(['period_start' => $encounterPeriod->start]);
    }
}
