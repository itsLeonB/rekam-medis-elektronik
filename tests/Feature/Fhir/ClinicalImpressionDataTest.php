<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class ClinicalImpressionDataTest extends FhirTestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'clinicalimpression';

    /**
     * Test apakah user dapat menlihat data prognosis
     */
    public function test_users_can_view_clinical_impression_data()
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
     * Test apakah user dapat membuat data prognosis baru
     */
    public function test_users_can_create_new_clinical_impression_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('clinicalimpression');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route('clinicalimpression.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('clinical_impression', $data['clinicalImpression']);
        $this->assertManyData('clinical_impression_investigation', $data['investigation']);
        $this->assertManyData('clinical_impression_finding', $data['finding']);
        $this->assertManyData('clinical_impression_note', $data['note']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('clinical_impression_identifier', ['system' => 'http://sys-ids.kemkes.go.id/clinicalimpression/' . $orgId, 'use' => 'official']);
    }

    /**
     * Test apakah user dapat memperbarui data prognosis
     */
    public function test_users_can_update_clinical_impression_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('clinicalimpression');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route('clinicalimpression.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['clinicalImpression']['id'] = $newData['id'];
        $data['clinicalImpression']['resource_id'] = $newData['resource_id'];
        $data['clinicalImpression']['status'] = 'completed';
        $response = $this->json('PUT', route('clinicalimpression.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('clinical_impression', $data['clinicalImpression']);
        $this->assertManyData('clinical_impression_investigation', $data['investigation']);
        $this->assertManyData('clinical_impression_finding', $data['finding']);
        $this->assertManyData('clinical_impression_note', $data['note']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('clinical_impression_identifier', ['system' => 'http://sys-ids.kemkes.go.id/clinicalimpression/' . $orgId, 'use' => 'official']);
    }
}
