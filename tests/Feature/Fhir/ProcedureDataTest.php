<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ProcedureDataTest extends TestCase
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

        $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['satusehat_id' => $newData['id']]));
        $response->assertStatus(200);
    }



    /**
     * Test apakah user dapat membuat data tindakan medis baru
     */
    public function test_users_can_create_new_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('procedure', 1);
        $this->assertDatabaseCount('codeable_concepts', 4);
        $this->assertDatabaseCount('codings', 4);
        $this->assertDatabaseCount('references', 3);
        $this->assertDatabaseCount('periods', 1);
        $this->assertDatabaseCount('procedure_performer', 1);
        $this->assertDatabaseCount('annotations', 1);
    }


    /**
     * Test apakah user dapat memperbarui data tindakan medis
     */
    public function test_users_can_update_procedure_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['status'] = 'in-progress';
        $newData['subject']['display'] = 'Budi Pekerti';

        $response = $this->json('PUT', route(self::RESOURCE_TYPE . '.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 2);
        $this->assertDatabaseCount('procedure', 1);
        $this->assertDatabaseCount('codeable_concepts', 4);
        $this->assertDatabaseCount('codings', 4);
        $this->assertDatabaseCount('references', 3);
        $this->assertDatabaseCount('periods', 1);
        $this->assertDatabaseCount('procedure_performer', 1);
        $this->assertDatabaseCount('annotations', 1);
    }
}
