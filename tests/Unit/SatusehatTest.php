<?php

namespace Tests\Unit;

use App\Http\Controllers\SatusehatController;
use App\Models\Fhir\Datatypes\Identifier;
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
        $user->assignRole('admin');

        $resType = 'Practitioner';
        $resId = 'N10000001';

        $response = $this->actingAs($user)->get(route('satusehat.resource.show', ['res_type' => $resType, 'res_id' => $resId]));

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
        $user->assignRole('admin');

        $idUses = Identifier::USE['binding']['valueset']['code'];
        $idUse = $idUses[array_rand($idUses)];

        $dataArray = [
            'resourceType' => 'Organization',
            'active' => fake()->boolean(),
            'name' => fake()->name(),
        ];

        $resType = $dataArray['resourceType'];

        $response = $this->actingAs($user)->post(route('satusehat.resource.store', ['res_type' => $resType]), $dataArray);

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
        $user->assignRole('admin');

        $satusehatId = config('app.organization_id');

        $dataArray = [
            'resourceType' => 'Organization',
            'id' => $satusehatId,
            'active' => fake()->boolean(),
            'name' => fake()->name(),
        ];

        $resType = $dataArray['resourceType'];

        $response = $this->actingAs($user)->put(route('satusehat.resource.update', ['res_type' => $resType, 'res_id' => $satusehatId]), $dataArray);

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
        $user->assignRole('admin');

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
        $user->assignRole('admin');

        $this->actingAs($user)->get(route('integration.show', ['res_type' => 'Patient', 'satusehat_id' => 'P02478375538']));

        $this->assertDatabaseCount('patient', 1);

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
        $user->assignRole('admin');

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
        $user->assignRole('admin');

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
        $user->assignRole('admin');

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
        $user->assignRole('admin');

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
        $user->assignRole('admin');

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

    public function test_search_encounter_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.encounter', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_condition_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.condition', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_condition_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => 'abc329d0-1692-4772-9340-e75df4b29eda'];
        $response = $this->actingAs($user)->get(route('satusehat.search.condition', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_observation_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.observation', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_observation_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '2823ed1d-3e3e-434e-9a5b-9c579d192787'];
        $response = $this->actingAs($user)->get(route('satusehat.search.observation', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_composition_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.composition', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_composition_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '2823ed1d-3e3e-434e-9a5b-9c579d192787'];
        $response = $this->actingAs($user)->get(route('satusehat.search.composition', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_procedure_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.procedure', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_procedure_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '2823ed1d-3e3e-434e-9a5b-9c579d192787'];
        $response = $this->actingAs($user)->get(route('satusehat.search.procedure', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_medication_request_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.medicationrequest', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_medication_request_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '2823ed1d-3e3e-434e-9a5b-9c579d192787'];
        $response = $this->actingAs($user)->get(route('satusehat.search.medicationrequest', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_allergy_intolerance()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['patient' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.allergyintolerance', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_clinical_impression_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.clinicalimpression', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_clinical_impression_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '2823ed1d-3e3e-434e-9a5b-9c579d192787'];
        $response = $this->actingAs($user)->get(route('satusehat.search.clinicalimpression', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_questionnaire_response_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => 'P02280547535'];
        $response = $this->actingAs($user)->get(route('satusehat.search.questionnaireresponse', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_questionnaire_response_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '66533eb2-723d-4e7c-b7aa-500cd67dd4c8'];
        $response = $this->actingAs($user)->get(route('satusehat.search.questionnaireresponse', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_service_request_by_subject()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.servicerequest', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_service_request_by_encounter()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['encounter' => '2823ed1d-3e3e-434e-9a5b-9c579d192787'];
        $response = $this->actingAs($user)->get(route('satusehat.search.servicerequest', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'total',
            'entry',
            'type'
        ]);
    }

    public function test_search_medication_statement()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $query = ['subject' => '100000030009'];
        $response = $this->actingAs($user)->get(route('satusehat.search.medicationstatement', $query));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'link',
            'type',
            'total',
            'entry'
        ]);
    }

    public function test_kfa()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

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
