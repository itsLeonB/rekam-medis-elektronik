<?php

namespace Tests\Feature;

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

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/procedure/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', 'api/procedure/' . $newData['resource_id']);
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
        $this->assertManyData('procedure_based_on', $data['basedOn']);
        $this->assertManyData('procedure_part_of', $data['partOf']);
        $this->assertManyData('procedure_performer', $data['performer']);
        $this->assertManyData('procedure_reason', $data['reason']);
        $this->assertManyData('procedure_body_site', $data['bodySite']);
        $this->assertManyData('procedure_report', $data['report']);
        $this->assertManyData('procedure_complication', $data['complication']);
        $this->assertManyData('procedure_follow_up', $data['followUp']);
        $this->assertManyData('procedure_note', $data['note']);
        $this->assertManyData('procedure_focal_device', $data['focalDevice']);
        $this->assertManyData('procedure_item_used', $data['itemUsed']);
    }


    /**
     * Test apakah user dapat memperbarui data tindakan medis
     */
    public function test_users_can_update_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('procedure');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/procedure/create', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['procedure']['id'] = $newData['id'];
        $data['procedure']['resource_id'] = $newData['resource_id'];
        $data['procedure']['subject'] = 'Patient/234234';
        $data['identifier'][0]['id'] = $newData['identifier'][0]['id'];
        $data['identifier'][0]['procedure_id'] = $newData['identifier'][0]['procedure_id'];
        $data['identifier'][0]['value'] = "5234341";

        $data['identifier'][] = [
            'system' => 'http://loinc.org',
            'use' => 'official',
            'value' => '1234567890'
        ];

        $response = $this->json('PUT', '/api/procedure/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('procedure', $data['procedure']);
        $this->assertManyData('procedure_identifier', $data['identifier']);
        $this->assertManyData('procedure_based_on', $data['basedOn']);
        $this->assertManyData('procedure_part_of', $data['partOf']);
        $this->assertManyData('procedure_performer', $data['performer']);
        $this->assertManyData('procedure_reason', $data['reason']);
        $this->assertManyData('procedure_body_site', $data['bodySite']);
        $this->assertManyData('procedure_report', $data['report']);
        $this->assertManyData('procedure_complication', $data['complication']);
        $this->assertManyData('procedure_follow_up', $data['followUp']);
        $this->assertManyData('procedure_note', $data['note']);
        $this->assertManyData('procedure_focal_device', $data['focalDevice']);
        $this->assertManyData('procedure_item_used', $data['itemUsed']);
    }
}
