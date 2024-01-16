<?php

namespace Tests\Unit;

use App\Models\Fhir\Resources\Encounter;
use App\Models\User;
use Database\Seeders\DummyDataSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DaftarPasienTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutModelEvents;

    private function setUpTestData(bool $patientEncounterOnly = true)
    {
        $seeder = new DummyDataSeeder();

        $seeder->seedOnboarding();

        if ($patientEncounterOnly) {
            return $seeder->makeDummies(true, true, 1);
        }

        return $seeder->makeDummies(true, false, 1);
    }

    private function assertCreated($layanan, $poli = null)
    {
        $encCount = Encounter::whereHas('class', function ($query) use ($layanan) {
            $query->where('code', config('app.kode_layanan.' . $layanan));
        });

        if ($poli) {
            $encCount = $encCount->whereHas('serviceType.coding', function ($query) use ($poli) {
                $query->where('code', config('app.kode_poli.' . $poli));
            });
        } elseif ($layanan == 'rawat-inap') {
            $encCount = $encCount->whereHas('serviceType.coding', function ($query) use ($poli) {
                $query->where('code', 124);
            });
        }

        $encCount = $encCount->count();

        $route = 'daftar-pasien.' . $layanan;

        if ($poli) {
            $route .= '.';
            $route .= $poli;
        }

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        if ($layanan == 'rawat-inap') {
            $response = $this->actingAs($admin)->get(route($route, ['serviceType' => 124]));
        } else {
            $response = $this->actingAs($admin)->get(route($route));
        }

        $response->assertStatus(200);
        $response->assertJsonCount($encCount);
        $response->assertJsonStructure([
            '*' => [
                'encounter_satusehat_id',
                'patient_satusehat_id',
                'patient_name',
                'patient_identifier',
                'period_start',
                'encounter_status',
                'practitioner_id',
                'practitioner_name',
                'procedure',
                'location'
            ]
        ]);
    }

    public function test_get_daftar_rawat_jalan_umum()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'umum');
    }

    public function test_get_daftar_rawat_jalan_neurologi()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'neurologi');
    }

    public function test_get_daftar_rawat_jalan_obgyn()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'obgyn');
    }

    public function test_get_daftar_rawat_jalan_gigi()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'gigi');
    }

    public function test_get_daftar_rawat_jalan_kulit()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'kulit');
    }

    public function test_get_daftar_rawat_jalan_ortopedi()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'ortopedi');
    }

    public function test_get_daftar_rawat_jalan_dalam()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'dalam');
    }

    public function test_get_daftar_rawat_jalan_bedah()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'bedah');
    }

    public function test_get_daftar_rawat_jalan_anak()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-jalan', 'anak');
    }

    public function test_get_daftar_rawat_inap()
    {
        $this->setUpTestData(false);

        $this->assertCreated('rawat-inap');
    }

    public function test_get_daftar_igd()
    {
        $this->setUpTestData(false);

        $this->assertCreated('igd');
    }
}
