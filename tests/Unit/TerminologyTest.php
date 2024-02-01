<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class TerminologyTest extends TestCase
{
    public function test_get_icd10()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.icd10'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'display_en', 'display_id']
        ]);
    }

    public function test_get_icd9cm_procedure()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.icd9cm-procedure'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'display', 'definition']
        ]);
    }

    public function test_get_loinc()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.loinc'));

        $response->assertStatus(200);
    }

    public function test_get_snomedct()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.snomed-ct'));

        $response->assertStatus(200);
    }

    public function test_get_provinsi()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.wilayah.provinsi'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['kode_provinsi', 'nama_provinsi']
        ]);
    }

    public function test_get_kabko()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.wilayah.kabko'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['kode_kabko', 'nama_kabko']
        ]);
    }

    public function test_get_kotalahir()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.wilayah.kotalahir'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['kode_kabko', 'nama_kabko', 'kode_provinsi', 'nama_provinsi']
        ]);
    }

    public function test_get_kecamatan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.wilayah.kecamatan'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['kode_kecamatan', 'nama_kecamatan']
        ]);
    }

    public function test_get_kelurahan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.wilayah.kelurahan'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['kode_kelurahan', 'nama_kelurahan']
        ]);
    }

    public function test_get_bcp13()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.bcp13'));

        $response->assertStatus(200);
    }

    public function test_get_bcp47()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.bcp47'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'display', 'definition']
        ]);
    }

    public function test_get_iso3166()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.iso3166'));

        $response->assertStatus(200);
    }

    public function test_get_ucum()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.ucum'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'unit']
        ]);
    }

    public function test_get_procedure_tindakan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.procedure.tindakan'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'display', 'definition']
        ]);
    }

    public function test_get_procedure_edukasi_bayi()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.procedure.edukasi-bayi'));

        $response->assertStatus(200);
    }

    public function test_get_procedure_other()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.procedure.other'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'display', 'definition']
        ]);
    }

    public function test_get_condition_kunjungan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.condition.kunjungan'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['system', 'code', 'display_en', 'display_id']
        ]);
    }

    public function test_get_condition_keluar()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.condition.keluar'));

        $response->assertStatus(200);
    }

    public function test_get_condition_keluhan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.condition.keluhan'));

        $response->assertStatus(200);
    }

    public function test_get_condition_riwayat_pribadi()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.condition.riwayat-pribadi'));

        $response->assertStatus(200);
    }

    public function test_get_condition_riwayat_keluarga()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.condition.riwayat-keluarga'));

        $response->assertStatus(200);
    }

    public function test_get_question_lokasi_kecelakaan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.questionnaire.lokasi-kecelakaan'));

        $response->assertStatus(200);
    }

    public function test_get_question_poli_tujuan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.questionnaire.poli-tujuan'));

        $response->assertStatus(200);
    }

    public function test_get_question_other()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('terminologi.questionnaire.other'));

        $response->assertStatus(200);
    }

    public function test_get_term()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $resTypes = array_keys(config('app.terminologi'));
        $resType = fake()->randomElement($resTypes);

        $attributes = array_keys(config('app.terminologi.' . $resType));
        $attribute = fake()->randomElement($attributes);

        $response = $this->get(route('terminologi.get', ['resourceType' => $resType, 'attribute' => $attribute]));

        $response->assertStatus(200);
    }
}
