<?php

namespace Tests\Feature\Fhir;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class QuestionnaireResponseDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    const RESOURCE_TYPE = 'questionnaireresponse';

    /**
     * Test apakah user dapat menlihat data hasil kuesioner
     */
    // public function test_users_can_view_questionnaire_response_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData(self::RESOURCE_TYPE);

    //     $headers = [
    //         'Content-Type' => 'application/json'
    //     ];
    //     $response = $this->json('POST', route(self::RESOURCE_TYPE. '.store'), $data, $headers);
    //     $newData = json_decode($response->getContent(), true);

    //     $response = $this->json('GET', route(self::RESOURCE_TYPE. '.show', ['res_id' => $newData['resource_id']]));
    //     $response->assertStatus(200);
    // }


    /**
     * Test apakah user dapat membuat data hasil kuesioner baru
     */
    // public function test_users_can_create_new_questionnaire_response_data()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $data = $this->getExampleData('QuestionnaireResponse');
    //     $headers = ['Content-Type' => 'application/json'];
    //     $response = $this->json('POST', route('questionnaireresponse.store'), $data, $headers);
    //     $response->assertStatus(201);
    // }


    /**
     * Test apakah user dapat memperbarui data hasil kuesioner
     */
    public function test_users_can_update_questionnaire_response_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('QuestionnaireResponse');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('questionnaireresponse.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $newData['subject']['display'] = 'Budi Pekerti';

        $response = $this->json('PUT', route('questionnaireresponse.update', ['satusehat_id' => $newData['id']]), $newData, $headers);
        $response->assertStatus(200);
    }
}
