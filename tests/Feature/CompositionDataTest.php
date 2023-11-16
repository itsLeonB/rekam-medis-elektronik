<?php

namespace Tests\Feature;

use App\Models\Composition;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class CompositionDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data diet pasien
     */
    public function test_users_can_view_composition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('composition');

        $resource = Resource::create(
            [
                'satusehat_id' => '000001',
                'res_type' => 'Composition',
                'res_ver' => 1
            ]
        );

        $compositionData = array_merge(['resource_id' => $resource->id], $data['composition']);

        Composition::create($compositionData);

        $response = $this->json('GET', 'api/composition/000001');
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
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/composition/create', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('composition', $data['composition']);
        $this->assertManyData('composition_category', $data['category']);
        $this->assertManyData('composition_author', $data['author']);
        $this->assertManyData('composition_attester', $data['attester']);
        $this->assertManyData('composition_relates_to', $data['relates_to']);
        $this->assertNestedData('composition_event', $data['event'], 'event_data', [
            [
                'table' => 'composition_event_code',
                'data' => 'code'
            ],
            [
                'table' => 'composition_event_detail',
                'data' => 'detail'
            ],
        ]);
        $this->assertNestedData('composition_section', $data['section'], 'section_data', [
            [
                'table' => 'composition_section_author',
                'data' => 'author'
            ],
            [
                'table' => 'composition_section_entry',
                'data' => 'entry'
            ],
        ]);
    }
}
