<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ClinicalImpressionDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'clinicalimpression';

    /**
     * Test apakah user dapat menlihat data prognosis
     */
    // public function test_users_can_view_clinical_impression_data()
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
     * Test apakah user dapat membuat data prognosis baru
     */
    // public function test_users_can_create_new_clinical_impression_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('ClinicalImpression');
    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', route('clinicalimpression.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }

    /**
     * Test apakah user dapat memperbarui data prognosis
     */
    public function test_users_can_update_clinical_impression_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('ClinicalImpression');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('clinicalimpression.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['description'] = 'Bapak Budi Pekerti terdiagnosa penyakit update';
        $newData['identifier'][0]['value'] = '1234567890';

        $response = $this->json('PUT', route('clinicalimpression.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
