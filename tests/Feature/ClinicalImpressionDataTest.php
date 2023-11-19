<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ClinicalImpressionDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data prognosis
     */
    public function test_users_can_view_clinical_impression_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('clinicalimpression');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/clinicalimpression/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/clinicalimpression/' . $newData['resource_id']);
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
        $response = $this->json('POST', '/api/clinicalimpression/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('clinical_impression', $data['clinicalImpression']);
        $this->assertManyData('clinical_impression_identifier', $data['identifier']);
        $this->assertManyData('clinical_impression_problem', $data['problem']);
        $this->assertNestedData('clinical_impression_investigation', $data['investigation'], 'investigation_data', [
            [
                'table' => 'clinic_impress_investigate_item',
                'data' => 'item'
            ]
        ]);
        $this->assertManyData('clinical_impression_protocol', $data['protocol']);
        $this->assertManyData('clinical_impression_finding', $data['finding']);
        $this->assertManyData('clinical_impression_prognosis', $data['prognosis']);
        $this->assertManyData('clinical_impression_support_info', $data['supportingInfo']);
        $this->assertManyData('clinical_impression_note', $data['note']);
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
        $response = $this->json('POST', '/api/clinicalimpression/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['clinicalImpression']['id'] = $newData['id'];
        $data['clinicalImpression']['resource_id'] = $newData['resource_id'];
        $data['clinicalImpression']['status'] = 'completed';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['impression_id'] = $newData['identifier'][0]['impression_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/clinicalimpression/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('clinical_impression', $data['clinicalImpression']);
        $this->assertManyData('clinical_impression_identifier', $data['identifier']);
        $this->assertManyData('clinical_impression_problem', $data['problem']);
        $this->assertNestedData('clinical_impression_investigation', $data['investigation'], 'investigation_data', [
            [
                'table' => 'clinic_impress_investigate_item',
                'data' => 'item'
            ]
        ]);
        $this->assertManyData('clinical_impression_protocol', $data['protocol']);
        $this->assertManyData('clinical_impression_finding', $data['finding']);
        $this->assertManyData('clinical_impression_prognosis', $data['prognosis']);
        $this->assertManyData('clinical_impression_support_info', $data['supportingInfo']);
        $this->assertManyData('clinical_impression_note', $data['note']);
    }
}
