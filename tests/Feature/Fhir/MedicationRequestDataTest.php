<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class MedicationRequestDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'MedicationRequest';

    /**
     * Test apakah user dapat menlihat data peresepan obat
     */
    public function test_users_can_view_medication_request_data()
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


    // /**
    //  * Test apakah user dapat membuat data peresepan obat baru
    //  */
    public function test_users_can_create_new_medication_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('medication_request', 1);
        $this->assertDatabaseCount('identifiers', 3);
        $this->assertDatabaseCount('codeable_concepts', 6);
        $this->assertDatabaseCount('codings', 5);
        $this->assertDatabaseCount('references', 5);
        $this->assertDatabaseCount('dosages', 1);
        $this->assertDatabaseCount('timings', 1);
        $this->assertDatabaseCount('timing_repeats', 1);
        $this->assertDatabaseCount('dose_and_rates', 1);
        $this->assertDatabaseCount('simple_quantities', 2);
        $this->assertDatabaseCount('med_req_dispense_request', 1);
        $this->assertDatabaseCount('durations', 2);
        $this->assertDatabaseCount('periods', 1);
    }


    // /**
    //  * Test apakah user dapat memperbarui data peresepan obat
    //  */
    public function test_users_can_update_medication_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData(self::RESOURCE_TYPE);
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['priority'] = 'stat';
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route(self::RESOURCE_TYPE . '.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);

        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 2);
        $this->assertDatabaseCount('medication_request', 1);
        $this->assertDatabaseCount('identifiers', 3);
        $this->assertDatabaseCount('codeable_concepts', 6);
        $this->assertDatabaseCount('codings', 5);
        $this->assertDatabaseCount('references', 5);
        $this->assertDatabaseCount('dosages', 1);
        $this->assertDatabaseCount('timings', 1);
        $this->assertDatabaseCount('timing_repeats', 1);
        $this->assertDatabaseCount('dose_and_rates', 1);
        $this->assertDatabaseCount('simple_quantities', 2);
        $this->assertDatabaseCount('med_req_dispense_request', 1);
        $this->assertDatabaseCount('durations', 2);
        $this->assertDatabaseCount('periods', 1);
    }
}
