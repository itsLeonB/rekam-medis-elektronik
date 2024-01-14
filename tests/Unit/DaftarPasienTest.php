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
        $response->assertJsonStructure([
            'daftar_pasien' => [
                '*' => [
                    'encounter_satusehat_id',
                    'patient_name',
                    'patient_identifier',
                    'period_start',
                    'encounter_status',
                    'encounter_practitioner',
                    'procedure'
                ]
            ]
        ]);
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
        $response->assertJsonStructure([
            'daftar_pasien' => [
                '*' => [
                    'encounter_satusehat_id',
                    'patient_name',
                    'patient_identifier',
                    'period_start',
                    'encounter_status',
                    'encounter_practitioner',
                    'procedure'
                ]
            ]
        ]);
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
        $response->assertJsonStructure([
            'daftar_pasien' => [
                '*' => [
                    'encounter_satusehat_id',
                    'patient_name',
                    'patient_identifier',
                    'period_start',
                    'encounter_practitioner',
                    'location'
                ]
            ]
        ]);
    }
}
