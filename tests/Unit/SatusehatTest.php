<?php

namespace Tests\Unit;

use App\Http\Controllers\SatusehatController;
use App\Models\User;
use Tests\TestCase;

class SatusehatTest extends TestCase
{
    public function test_get_auth_token(): void
    {
        $controller = new SatusehatController();
        $token = $controller->getToken();
        $this->assertIsString($token);
    }

    public function test_get_resource(): void
    {
        $user = User::factory()->create();

        $resType = 'Practitioner';
        $resId = 'N10000001';

        $response = $this->actingAs($user)->get(route('integration.show', ['resourceType' => $resType, 'id' => $resId]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta'
        ]);
    }

    public function test_post_resource(): void
    {
        $user = User::factory()->create();

        $dataArray = [
            'resourceType' => 'Organization',
            'active' => fake()->boolean(),
            'name' => fake()->name(),
        ];

        $resType = $dataArray['resourceType'];

        $response = $this->actingAs($user)->post(route('integration.store', ['resourceType' => $resType]), $dataArray);

        $response->assertStatus(201);
        $response->assertJsonFragment(['resourceType' => $resType]);
        $response->assertJsonFragment(['name' => $dataArray['name']]);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta'
        ]);
    }

    public function test_put_resource(): void
    {
        $user = User::factory()->create();

        $satusehatId = config('app.organization_id');

        $dataArray = [
            'resourceType' => 'Organization',
            'id' => $satusehatId,
            'active' => fake()->boolean(),
            'name' => fake()->name(),
        ];

        $resType = $dataArray['resourceType'];

        $response = $this->actingAs($user)->put(route('integration.update', ['resourceType' => $resType, 'id' => $satusehatId]), $dataArray);

        $response->assertStatus(200);
        $response->assertJsonFragment(['resourceType' => $resType]);
        $response->assertJsonFragment(['id' => $dataArray['id']]);
        $response->assertJsonFragment(['name' => $dataArray['name']]);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta'
        ]);
    }

    public function test_read_consent()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('satusehat.consent.show', ['patient_id' => 'P02478375538']));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'status',
            'scope',
            'category',
            'patient',
            'dateTime',
            'organization',
            'policyRule',
            'provision'
        ]);
    }

    public function test_update_consent()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('integration.show', ['resourceType' => 'Patient', 'id' => 'P02478375538']));

        $consentData = [
            'patient_id' => 'P02478375538',
            'action' => 'OPTOUT',
            'agent' => fake()->name()
        ];

        $response = $this->actingAs($user)->post(route('satusehat.consent.store'), $consentData);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'status',
            'scope',
            'category',
            'patient',
            'dateTime',
            'organization',
            'policyRule',
            'provision'
        ]);
    }

    public function test_search_practitioner_by_nik()
    {
        $user = User::factory()->create();

        $nik = 'https://fhir.kemkes.go.id/id/nik|367400001111222';
        $response = $this->actingAs($user)->get(route('satusehat.search.practitioner', ['identifier' => $nik]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_practitioner_by_name()
    {
        $user = User::factory()->create();

        $query = [
            'name' => 'Voigt',
            'gender' => 'male',
            'birthdate' => '1945'
        ];
        $response = $this->actingAs($user)->get(route('satusehat.search.practitioner', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_patient_by_identifier()
    {
        $user = User::factory()->create();

        $query = ['identifier' => 'https://fhir.kemkes.go.id/id/nik|9271060312000001'];
        $response = $this->actingAs($user)->get(route('satusehat.search.patient', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);

        $query = ['identifier' => 'https://fhir.kemkes.go.id/id/nik-ibu|367400001111222'];
        $response = $this->actingAs($user)->get(route('satusehat.search.patient', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_patient_by_name_birthdate_nik()
    {
        $user = User::factory()->create();

        $query = [
            'name' => 'patient',
            'birthdate' => '1980-12-03',
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|9271060312000001'
        ];
        $response = $this->actingAs($user)->get(route('satusehat.search.patient', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_patient_by_name_birthdate_gender()
    {
        $user = User::factory()->create();

        $query = [
            'name' => 'patient 1',
            'birthdate' => '1980-12-03',
            'gender' => 'male'
        ];
        $response = $this->actingAs($user)->get(route('satusehat.search.patient', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_kfa()
    {
        $user = User::factory()->create();

        $query = [
            'page' => 1,
            'size' => 10,
            'product_type' => 'farmasi',
            'keyword' => 'paracetamol',
        ];
        $response = $this->actingAs($user)->get(route('satusehat.kfa', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'page',
            'size',
            'items'
        ]);
    }
}
