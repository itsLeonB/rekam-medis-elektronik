<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class MedicationDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data obat
     */
    public function test_users_can_view_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('medication.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route('resource.show', ['res_type' => 'medication', 'res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data obat baru
     */
    public function test_users_can_create_new_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('medication.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('medication', $data['medication']);
        $this->assertManyData('medication_ingredient', $data['ingredient']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('medication_identifier', ['system' => 'http://sys-ids.kemkes.go.id/medication/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data obat
     */
    public function test_users_can_update_medication_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('medication');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('medication.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['medication']['id'] = $newData['id'];
        $data['medication']['resource_id'] = $newData['resource_id'];
        $data['medication']['status'] = 'inactive';
        $response = $this->json('PUT', route('medication.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('medication', $data['medication']);
        $this->assertManyData('medication_ingredient', $data['ingredient']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('medication_identifier', ['system' => 'http://sys-ids.kemkes.go.id/medication/' . $orgId, 'use' => 'official']);
    }
}
