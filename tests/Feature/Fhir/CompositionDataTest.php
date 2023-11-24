<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
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
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('composition');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/composition', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/composition/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data diet pasien baru
     */
    public function test_users_can_create_new_composition_data()
    {
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('composition');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/composition', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('composition', $data['composition']);
        $this->assertManyData('composition_category', $data['category']);
        $this->assertManyData('composition_author', $data['author']);
        $this->assertManyData('composition_attester', $data['attester']);
        $this->assertManyData('composition_relates_to', $data['relatesTo']);
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
        $orgId = env('organization_id');
        $this->assertDatabaseHas('composition', ['identifier_system' => 'http://sys-ids.kemkes.go.id/composition/' . $orgId, 'identifier_use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data diet pasien
     */
    public function test_users_can_update_composition_data()
    {
        Config::set('organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('composition');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/composition', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['composition']['id'] = $newData['id'];
        $data['composition']['resource_id'] = $newData['resource_id'];
        $data['author'][0]['id'] = $newData['author'][0]['id'];
        $data['author'][0]['composition_id'] = $newData['author'][0]['composition_id'];
        $data['author'][0]['reference'] = "Practitioner/00001";

        $data['author'][] = [
            'reference' => 'Practitioner/00002'
        ];

        $response = $this->json('PUT', '/api/composition/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('composition', $data['composition']);
        $this->assertManyData('composition_category', $data['category']);
        $this->assertManyData('composition_author', $data['author']);
        $this->assertManyData('composition_attester', $data['attester']);
        $this->assertManyData('composition_relates_to', $data['relatesTo']);
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
        $orgId = env('organization_id');
        $this->assertDatabaseHas('composition', ['identifier_system' => 'http://sys-ids.kemkes.go.id/composition/' . $orgId, 'identifier_use' => 'official']);
    }
}