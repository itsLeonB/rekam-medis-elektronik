<?php

namespace Tests\Feature;

use App\Models\AllergyIntolerance;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ExamplePayload;

class AllergyIntoleranceDataTest extends TestCase
{
    use DatabaseTransactions;
    use ExamplePayload;

    /**
     * Test apakah user dapat menlihat data alergi pasien
     */
    public function test_users_can_view_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'AllergyIntolerance',
                'res_ver' => 1
            ]
        );

        $allergyData = array_merge(['resource_id' => $resource->id], $data['allergy_intolerance']);

        AllergyIntolerance::create($allergyData);

        $response = $this->json('GET', 'api/allergyintolerance/000001');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data alergi pasien baru
     */
    public function test_users_can_create_new_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('allergyintolerance');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/allergyintolerance/create', $data, $headers);
        $response->assertStatus(201);
    }
}
