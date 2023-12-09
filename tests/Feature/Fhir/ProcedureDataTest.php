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

    const RESOURCE_TYPE = 'procedure';

    /**
     * Test apakah user dapat menlihat data tindakan medis
     */
    public function test_users_can_view_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['res_id' => $newData['resource_id']]));
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
        $this->assertManyData('procedure_performer', $data['performer']);
        $this->assertManyData('procedure_note', $data['note']);
        $this->assertManyData('procedure_focal_device', $data['focalDevice']);
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
        $this->assertManyData('procedure_performer', $data['performer']);
        $this->assertManyData('procedure_note', $data['note']);
        $this->assertManyData('procedure_focal_device', $data['focalDevice']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('procedure_identifier', ['system' => 'http://sys-ids.kemkes.go.id/procedure/' . $orgId, 'use' => 'official']);
    }
}
