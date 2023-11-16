<?php

namespace Tests\Feature;

use App\Models\Procedure;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ProcedureDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

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

        $this->assertMainData('procedure', $data['procedure']);
        $this->assertManyData('procedure_identifier', $data['identifier']);
        $this->assertManyData('procedure_based_on', $data['based_on']);
        $this->assertManyData('procedure_part_of', $data['part_of']);
        $this->assertManyData('procedure_performer', $data['performer']);
        $this->assertManyData('procedure_reason', $data['reason']);
        $this->assertManyData('procedure_body_site', $data['body_site']);
        $this->assertManyData('procedure_report', $data['report']);
        $this->assertManyData('procedure_complication', $data['complication']);
        $this->assertManyData('procedure_follow_up', $data['follow_up']);
        $this->assertManyData('procedure_note', $data['note']);
        $this->assertManyData('procedure_focal_device', $data['focal_device']);
        $this->assertManyData('procedure_item_used', $data['item_used']);
    }
}
