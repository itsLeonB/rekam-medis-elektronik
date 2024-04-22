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
    }

    public function test_get_daftar_rawat_jalan_umum()
    {
        $this->assertCreated('rawat-jalan', 'umum');
    }

    public function test_get_daftar_rawat_jalan_neurologi()
    {
        $this->assertCreated('rawat-jalan', 'neurologi');
    }

    public function test_get_daftar_rawat_jalan_obgyn()
    {
        $this->assertCreated('rawat-jalan', 'obgyn');
    }

    public function test_get_daftar_rawat_jalan_gigi()
    {
        $this->assertCreated('rawat-jalan', 'gigi');
    }

    public function test_get_daftar_rawat_jalan_kulit()
    {
        $this->assertCreated('rawat-jalan', 'kulit');
    }

    public function test_get_daftar_rawat_jalan_ortopedi()
    {
        $this->assertCreated('rawat-jalan', 'ortopedi');
    }

    public function test_get_daftar_rawat_jalan_dalam()
    {
        $this->assertCreated('rawat-jalan', 'dalam');
    }

    public function test_get_daftar_rawat_jalan_bedah()
    {
        $this->assertCreated('rawat-jalan', 'bedah');
    }

    public function test_get_daftar_rawat_jalan_anak()
    {
        $this->assertCreated('rawat-jalan', 'anak');
    }

    public function test_get_daftar_rawat_inap()
    {
        $this->assertCreated('rawat-inap');
    }

    public function test_get_daftar_igd()
    {
        $this->assertCreated('igd');
    }
}
