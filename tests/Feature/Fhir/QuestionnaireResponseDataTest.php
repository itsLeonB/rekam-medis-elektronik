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

    /**
     * Test apakah user dapat menlihat data hasil kuesioner
     */
    public function test_users_can_view_questionnaire_response_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('questionnaireresponse');

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('questionnaireresponse.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', route('resource.show', ['res_type' => 'questionnaireresponse', 'res_id' => $newData['resource_id']]));
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data hasil kuesioner baru
     */
    public function test_users_can_create_new_questionnaire_response_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('questionnaireresponse');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('questionnaireresponse.store'), $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('questionnaire_response', $data['questionnaireResponse']);
        $this->assertManyData('questionnaire_response_item', $data['item']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('questionnaire_response', ['identifier_system' => 'http://sys-ids.kemkes.go.id/questionnaireresponse/' . $orgId, 'identifier_use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data hasil kuesioner
     */
    public function test_users_can_update_questionnaire_response_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('questionnaireresponse');
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->json('POST', route('questionnaireresponse.store'), $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['questionnaireresponse']['id'] = $newData['id'];
        $data['questionnaireresponse']['resource_id'] = $newData['resource_id'];
        $data['questionnaireresponse']['status'] = 'completed';
        $response = $this->json('PUT', route('questionnaireresponse.update', ['res_id' => $newData['resource_id']]), $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('questionnaire_response', $data['questionnaireResponse']);
        $this->assertManyData('questionnaire_response_item', $data['item']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('questionnaire_response', ['identifier_system' => 'http://sys-ids.kemkes.go.id/questionnaireresponse/' . $orgId, 'identifier_use' => 'official']);
    }
}
