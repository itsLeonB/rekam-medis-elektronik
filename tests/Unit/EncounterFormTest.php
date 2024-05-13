<?php

namespace Tests\Unit;

use App\Models\FhirResource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EncounterFormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index_practitioner()
    {
        $user = User::factory()->create();

        $count = fake()->numberBetween(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $practitioner = FhirResource::factory()->specific('Practitioner')->create();
        }

        $response = $this->actingAs($user)->get(route('form.index.encounter'));

        $response->assertStatus(200);
        $response->assertJsonCount($count);
        $response->assertJsonStructure([
            '*' => [
                'satusehat_id',
                'name',
            ]
        ]);
    }

    public function test_index_location()
    {
        $user = User::factory()->create();

        $count = fake()->numberBetween(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $location = FhirResource::factory()->specific('Location')->create();
        }

        $response = $this->actingAs($user)->get(route('form.index.location'));

        $response->assertStatus(200);
        $response->assertJsonCount($count);
        $response->assertJsonStructure([
            '*' => [
                'satusehat_id',
                'identifier',
                'name',
                'serviceClass',
            ]
        ]);
        $response->assertJsonFragment([
            'satusehat_id' => data_get($location, 'id'),
            'identifier' => data_get($location, 'identifier.0.value'),
            'name' => data_get($location, 'name'),
            'serviceClass' => data_get($location, 'serviceClass.valueCodeableConcept.coding.0.display'),
        ]);
    }

    public function test_get_organization()
    {
        $user = User::factory()->create();

        $induk = FhirResource::factory()->specific('Organization')->create(['id' => config('app.organization_id')]);
        $rawatJalan = FhirResource::factory()->specific('Organization')->create(['id' => config('app.rawat_jalan_org_id')]);
        $rawatInap = FhirResource::factory()->specific('Organization')->create(['id' => config('app.rawat_inap_org_id')]);
        $igd = FhirResource::factory()->specific('Organization')->create(['id' => config('app.igd_org_id')]);

        $jenisLayanan = [
            'induk' => $induk,
            'rawat_jalan' => $rawatJalan,
            'rawat_inap' => $rawatInap,
            'igd' => $igd
        ];

        foreach ($jenisLayanan as $layanan => $organization) {
            $response = $this->actingAs($user)->get(route('form.ref.organization', ['layanan' => $layanan]));

            $response->assertStatus(200);
            $response->assertJsonFragment([
                'reference' => 'Organization/' . data_get($organization, 'id'),
                'display' => data_get($organization, 'name')
            ]);
        }
    }
}
