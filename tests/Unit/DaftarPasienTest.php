<?php

namespace Tests\Unit;

use App\Models\FhirResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DaftarPasienTest extends TestCase
{
    use DatabaseTransactions;

    private function assertCreated($layanan, $poli = null)
    {
        $encCount = FhirResource::where('class.code', config('app.kode_layanan.' . $layanan));

        if ($poli) {
            $encCount = $encCount->where('serviceType.coding.code', config('app.kode_poli.' . $poli));
        } elseif ($layanan == 'rawat-inap') {
            $encCount = $encCount->where('serviceType.coding.code', 124);
        }

        $encCount = $encCount->count();

        $route = 'daftar-pasien.' . $layanan;

        if ($poli) {
            $route .= '.';
            $route .= $poli;
        }

        Role::create(['name' => 'admin']);
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
