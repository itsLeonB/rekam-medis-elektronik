<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ConditionDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data kondisi pasien
     */
    public function test_users_can_view_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('condition');

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'Condition',
                'res_ver' => 1
            ]
        );

        $conditionData = array_merge(['resource_id' => $resource->id], $data['condition']);

        Condition::create($conditionData);

        $response = $this->json('GET', 'api/condition/000001');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data kondisi pasien baru
     */
    public function test_users_can_create_new_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('condition');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/condition/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('condition', $data['condition']);
        $this->assertManyData('condition_identifier', $data['identifier']);
        $this->assertManyData('condition_category', $data['category']);
        $this->assertManyData('condition_body_site', $data['body_site']);
        $this->assertNestedData('condition_stage', $data['stage'], 'stage_data', [
            [
                'table' => 'condition_stage_assessment',
                'data' => 'assessment'
            ]
        ]);
        $this->assertManyData('condition_evidence', $data['evidence']);
        $this->assertManyData('condition_note', $data['note']);
    }
}
