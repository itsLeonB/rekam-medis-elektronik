<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ClinicalImpressionDataTest extends TestCase
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
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['satusehat_id' => $newData['id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data prognosis baru
     */
    public function test_users_can_create_new_clinical_impression_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('clinical_impression', 1);
        $this->assertDatabaseCount('identifiers', 2);
        $this->assertDatabaseCount('references', 7);
        $this->assertDatabaseCount('clinical_impression_investigation', 1);
        $this->assertDatabaseCount('codeable_concepts', 3);
        $this->assertDatabaseCount('codings', 2);
        $this->assertDatabaseCount('clinical_impression_finding', 1);
    }

    /**
     * Test apakah user dapat memperbarui data prognosis
     */
    public function test_users_can_update_clinical_impression_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['description'] = 'Bapak Budi Pekerti terdiagnosa penyakit update';
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route(self::RESOURCE_TYPE . '.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 2);
        $this->assertDatabaseCount('clinical_impression', 1);
        $this->assertDatabaseCount('identifiers', 2);
        $this->assertDatabaseCount('references', 7);
        $this->assertDatabaseCount('clinical_impression_investigation', 1);
        $this->assertDatabaseCount('codeable_concepts', 3);
        $this->assertDatabaseCount('codings', 2);
        $this->assertDatabaseCount('clinical_impression_finding', 1);
    }
}
