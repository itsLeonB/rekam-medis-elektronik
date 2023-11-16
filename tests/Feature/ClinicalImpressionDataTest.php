<?php

namespace Tests\Feature;

use App\Models\ClinicalImpression;
use App\Models\Resource;
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

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'ClinicalImpression',
                'res_ver' => 1
            ]
        );

        $clinicalImpressionData = array_merge(['resource_id' => $resource->id], $data['clinical_impression']);

        ClinicalImpression::create($clinicalImpressionData);

        $response = $this->json('GET', 'api/clinicalimpression/000001');
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

        $this->assertMainData('clinical_impression', $data['clinical_impression']);
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
        $this->assertManyData('clinical_impression_support_info', $data['supporting_info']);
        $this->assertManyData('clinical_impression_note', $data['note']);
    }
}
