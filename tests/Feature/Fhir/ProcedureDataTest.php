<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\FhirTestCase;
use Tests\Traits\FhirTest;

class ProcedureDataTest extends FhirTestCase
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

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('procedure.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route('resource.show', ['res_type' => 'procedure', 'res_id' => $newData['resource_id']]));
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
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('procedure.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('procedure', $data['procedure']);
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
        $orgId = env('organization_id');
        $this->assertDatabaseHas('procedure_identifier', ['system' => 'http://sys-ids.kemkes.go.id/procedure/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data tindakan medis
     */
    public function test_users_can_update_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('procedure');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('procedure.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['procedure']['id'] = $newData['id'];
        $data['procedure']['resource_id'] = $newData['resource_id'];
        $data['procedure']['subject'] = 'Patient/234234';
        $response = $this->json('PUT', route('procedure.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('procedure', $data['procedure']);
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
        $orgId = env('organization_id');
        $this->assertDatabaseHas('procedure_identifier', ['system' => 'http://sys-ids.kemkes.go.id/procedure/' . $orgId, 'use' => 'official']);
    }
}
