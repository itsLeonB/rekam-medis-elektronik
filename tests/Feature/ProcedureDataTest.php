<?php

namespace Tests\Feature;

use App\Models\Procedure;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ExamplePayload;

class ProcedureDataTest extends TestCase
{
    use DatabaseTransactions;
    use ExamplePayload;

    /**
     * Test apakah user dapat menlihat data tindakan medis
     */
    public function test_users_can_view_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('procedure');

        $resource = Resource::create(
            [
                'satusehat_id' => 'P000000',
                'res_type' => 'Procedure',
                'res_ver' => 1
            ]
        );

        $procedureData = array_merge(['resource_id' => $resource->id], $data['procedure']);

        Procedure::create($procedureData);

        $response = $this->json('GET', 'api/procedure/P000000');
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data tindakan medis baru
     */
    public function test_users_can_create_new_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('procedure');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/procedure/create', $data, $headers);
        $response->assertStatus(201);
    }
}
