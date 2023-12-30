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

    const RESOURCE_TYPE = 'medicationrequest';

    /**
     * Test apakah user dapat menlihat data peresepan obat
     */
    // public function test_users_can_view_medication_request_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData(self::RESOURCE_TYPE);

    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', route(self::RESOURCE_TYPE . '.store'), $data, $headers);
    //     $newData = json_decode($response->getContent(), true);

    //     $response = $this->json('GET', route(self::RESOURCE_TYPE . '.show', ['res_id' => $newData['resource_id']]));
    //     $response->assertStatus(200);
    // }


    // /**
    //  * Test apakah user dapat membuat data peresepan obat baru
    //  */
    // public function test_users_can_create_new_medication_request_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('MedicationRequest');
    //     $headers = ['Content-Type' => 'application/json'];
    //     $response = $this->json('POST', route('medicationrequest.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    // /**
    //  * Test apakah user dapat memperbarui data peresepan obat
    //  */
    public function test_users_can_update_medication_request_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('MedicationRequest');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('medicationrequest.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['priority'] = 'stat';
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route('medicationrequest.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);}
}
