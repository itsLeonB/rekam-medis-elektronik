<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ConditionDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'condition';

    /**
     * Test apakah user dapat menlihat data kondisi pasien
     */
    // public function test_users_can_view_condition_data()
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
     * Test apakah user dapat membuat data kondisi pasien baru
     */
    // public function test_users_can_create_new_condition_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('Condition');
    //     $headers = ['Content-Type' => 'application/json'];
    //     $response = $this->json('POST', route('condition.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    /**
     * Test apakah user dapat memperbarui data kondisi pasien
     */
    public function test_users_can_update_condition_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('Condition');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('condition.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['onsetDateTime'] = '2022-06-15';
        $newData['encounter']['display'] = 'Kunjungan update';

        $response = $this->json('PUT', route('condition.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
