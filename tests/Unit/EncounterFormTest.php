<?php

namespace Tests\Unit;

use App\Models\Fhir\Datatypes\HumanName;
use App\Models\Fhir\Resources\Location;
use App\Models\Fhir\Resources\Medication;
use App\Models\Fhir\Resources\Organization;
use App\Models\Fhir\Resources\Practitioner;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EncounterFormTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index_practitioner()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $count = fake()->numberBetween(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $practitioner = Practitioner::factory()->create();
            HumanName::factory()->for($practitioner, 'humanNameable')->create();
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
        $user->assignRole('admin');

        $count = fake()->numberBetween(1, 10);

        for ($i = 0; $i < $count; $i++) {
            $location = Location::factory()->create();
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
            'satusehat_id' => data_get($location, 'resource.satusehat_id'),
            'identifier' => data_get($location, 'identifier.0.value'),
            'name' => data_get($location, 'name'),
            'serviceClass' => data_get($location, 'serviceClass.valueCodeableConcept.coding.0.display'),
        ]);
    }

    public function test_get_organization()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $rawatJalan = Organization::factory()->rawatJalan()->create();
        $rawatInap = Organization::factory()->rawatInap()->create();
        $igd = Organization::factory()->igd()->create();

        $jenisLayanan = [
            'rawat_jalan' => $rawatJalan,
            'rawat_inap' => $rawatInap,
            'igd' => $igd
        ];

        foreach ($jenisLayanan as $layanan => $organization) {
            $response = $this->actingAs($user)->get(route('form.ref.organization', ['layanan' => $layanan]));

            $response->assertStatus(200);
            $response->assertJsonFragment([
                'reference' => 'Organization/' . data_get($organization, 'resource.satusehat_id'),
                'display' => data_get($organization, 'name')
            ]);
        }
    }

    public function test_index_medication()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $count = fake()->numberBetween(1, 10);

        for ($i = 0; $i < $count; $i++) {
            Medication::factory()->create();
        }

        $response = $this->actingAs($user)->get(route('form.index.medication'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'satusehat_id',
                    'code' => [
                        'system',
                        'code',
                        'display',
                    ],
                    'form' => [
                        'system',
                        'code',
                        'display',
                    ],
                    'medicationType' => [
                        'system',
                        'code',
                        'display',
                    ],
                ]
            ]
        ]);
    }
}
