<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class CompositionDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'composition';

    /**
     * Test apakah user dapat menlihat data diet pasien
     */
    public function test_users_can_view_composition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE. '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE. '.show', ['res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data diet pasien baru
     */
    public function test_users_can_create_new_composition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('composition');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('composition.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('composition', $data['composition']);
        $this->assertManyData('composition_attester', $data['attester']);
        $this->assertManyData('composition_relates_to', $data['relatesTo']);
        $this->assertManyData('composition_event', $data['event']);
        $this->assertManyData('composition_section', $data['section']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('composition', ['identifier_system' => 'http://sys-ids.kemkes.go.id/composition/' . $orgId, 'identifier_use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data diet pasien
     */
    public function test_users_can_update_composition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('composition');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('composition.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['composition']['id'] = $newData['id'];
        $data['composition']['resource_id'] = $newData['resource_id'];

        $data['author'][] = 'Practitioner/00002';

        $response = $this->json('PUT', route('composition.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('composition', $data['composition']);
        $this->assertManyData('composition_attester', $data['attester']);
        $this->assertManyData('composition_relates_to', $data['relatesTo']);
        $this->assertManyData('composition_event', $data['event']);
        $this->assertManyData('composition_section', $data['section']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('composition', ['identifier_system' => 'http://sys-ids.kemkes.go.id/composition/' . $orgId, 'identifier_use' => 'official']);
    }
}
