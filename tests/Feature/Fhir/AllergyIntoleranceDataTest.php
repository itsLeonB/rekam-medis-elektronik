<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class AllergyIntoleranceDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'allergyintolerance';

    /**
     * Test apakah user dapat menlihat data alergi pasien
     */
    // public function test_users_can_view_allergy_intolerance_data()
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


    /**
     * Test apakah user dapat membuat data alergi pasien baru
     */
    // public function test_users_can_create_new_allergy_intolerance_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('AllergyIntolerance');

    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', route('allergyintolerance.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    /**
     * Test apakah user dapat memperbarui data alergi pasien
     */
    public function test_users_can_update_allergy_intolerance_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('AllergyIntolerance');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('allergyintolerance.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['category'] = ['food', 'environment'];
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route('allergyintolerance.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
