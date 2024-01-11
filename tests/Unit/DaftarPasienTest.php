<?php

namespace Tests\Unit;

use App\Http\Controllers\DaftarPasienController;
use App\Models\Fhir\Datatypes\CodeableConcept;
use App\Models\Fhir\Datatypes\Coding;
use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Datatypes\Identifier;
use App\Models\Fhir\Datatypes\Period;
use App\Models\Fhir\Datatypes\Reference;
use App\Models\Fhir\Resources\Encounter;
use App\Models\Fhir\Resources\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DaftarPasienTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutModelEvents;

    private function fakePatientAndEncounter(int $count)
    {
        $patients = [];
        $encounters = [];

        for ($i = 0; $i < $count; $i++) {
            $patient = Patient::factory()->create();

            HumanName::factory()
                ->for($patient, 'humanNameable')
                ->create(['attr_type' => 'name']);

            Identifier::factory()
                ->rekamMedis()
                ->for($patient, 'identifiable')
                ->create(['attr_type' => 'identifier']);

            $encounter = Encounter::factory()->create();

            CodeableConcept::factory()
                ->for($encounter, 'codeable')
                ->has(Coding::factory()->encounterClass(), 'coding')
                ->create(['attr_type' => 'class']);

            CodeableConcept::factory()
                ->for($encounter, 'codeable')
                ->has(Coding::factory()->encounterServiceType(), 'coding')
                ->create(['attr_type' => 'serviceType']);

            Period::factory()
                ->for($encounter, 'periodable')
                ->create();

            Reference::factory()
                ->for($encounter, 'referenceable')
                ->create([
                    'reference' => 'Patient/' . $patient->resource->satusehat_id,
                    'attr_type' => 'subject'
                ]);

            $patients[] = $patient;
            $encounters[] = $encounter;
        }

        return [$patients, $encounters];
    }

    public function test_map_encounter_to_patient()
    {
        [$patients, $encounters] = $this->fakePatientAndEncounter(5);

        $controller = new DaftarPasienController();

        $response = $controller->mapEncounterToPatient(collect($encounters));

        $this->assertEquals($response->count(), 5);
        $this->assertEquals($response->first()['encounter_satusehat_id'], $encounters[0]->resource->satusehat_id);
        $this->assertEquals($response->first()['patient_name'], $patients[0]->name()->first()->text);
        $this->assertEquals($response->first()['patient_identifier'], $patients[0]->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->first()->value);
        $this->assertEquals($response->first()['period_start'], $encounters[0]->period->start);

        $this->assertEquals($response->last()['encounter_satusehat_id'], $encounters[4]->resource->satusehat_id);
        $this->assertEquals($response->last()['patient_name'], $patients[4]->name()->first()->text);
        $this->assertEquals($response->last()['patient_identifier'], $patients[4]->identifier()->where('system', config('app.identifier_systems.patient.rekam-medis'))->first()->value);
        $this->assertEquals($response->last()['period_start'], $encounters[4]->period->start);
    }

    public function test_get_daftar_rawat_jalan()
    {
        $count = fake()->numberBetween(1, 10);
        [$patients, $encounters] = $this->fakePatientAndEncounter($count);

        $serviceType = $encounters[0]->serviceType->coding->first()->code;
        $encCount = Encounter::whereHas('class', function ($query) {
            $query->where('code', 'AMB');
        })
            ->whereHas('serviceType.coding', function ($query) use ($serviceType) {
                $query->where('code', $serviceType);
            })->count();

        $response = $this->get(route('daftar-pasien.rawat-jalan', ['serviceType' => $serviceType]));

        $response->assertStatus(200);
        $response->assertJsonCount($encCount, 'daftar_pasien');
    }

    public function test_get_daftar_rawat_inap()
    {
        $count = fake()->numberBetween(1, 10);
        [$patients, $encounters] = $this->fakePatientAndEncounter($count);

        $serviceType = $encounters[0]->serviceType->coding->first()->code;
        $encCount = Encounter::whereHas('class', function ($query) {
            $query->where('code', 'IMP');
        })
            ->whereHas('serviceType.coding', function ($query) use ($serviceType) {
                $query->where('code', $serviceType);
            })->count();

        $response = $this->get(route('daftar-pasien.rawat-inap', ['serviceType' => $serviceType]));

        $response->assertStatus(200);
        $response->assertJsonCount($encCount, 'daftar_pasien');
    }

    public function test_get_daftar_igd()
    {
        $count = fake()->numberBetween(1, 10);
        [$patients, $encounters] = $this->fakePatientAndEncounter($count);

        $encCount = Encounter::whereHas('class', function ($query) {
            $query->where('code', 'EMER');
        })->count();

        $response = $this->get(route('daftar-pasien.igd'));

        $response->assertStatus(200);
        $response->assertJsonCount($encCount, 'daftar_pasien');
    }
}
