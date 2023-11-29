<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class ImagingStudyDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data hasil radiologi
     */
    public function test_users_can_view_imaging_study_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('imagingstudy');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/imagingstudy', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', '/api/imagingstudy/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data hasil radiologi baru
     */
    public function test_users_can_create_new_imaging_study_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('imagingstudy');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/imagingstudy', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('imaging_study', $data['imagingStudy']);
        $this->assertManyData('imaging_study_reason_code', $data['reasonCode']);
        $this->assertManyData('imaging_study_note', $data['note']);
        $this->assertNestedData('imaging_study_series', $data['series'], 'series_data', [
            [
                'table' => 'img_study_series_instance',
                'data' => 'instance'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('imaging_study_identifier', ['system' => 'http://sys-ids.kemkes.go.id/acsn/' . $orgId, 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data hasil radiologi
     */
    public function test_users_can_update_imaging_study_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('imagingstudy');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/imagingstudy', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['imagingStudy']['id'] = $newData['id'];
        $data['imagingStudy']['resource_id'] = $newData['resource_id'];
        $data['imagingStudy']['status'] = 'available';
        $response = $this->json('PUT', '/api/imagingstudy/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);
        $updatedResponse = $this->json('GET', '/api/imagingstudy/' . $newData['resource_id']);
        $updatedData = json_decode($updatedResponse->getContent(), true);
        $this->assertEquals('available', $updatedData['status']);

        $this->assertMainData('imaging_study', $data['imagingStudy']);
        $this->assertManyData('imaging_study_reason_code', $data['reasonCode']);
        $this->assertManyData('imaging_study_note', $data['note']);
        $this->assertNestedData('imaging_study_series', $data['series'], 'series_data', [
            [
                'table' => 'img_study_series_instance',
                'data' => 'instance'
            ]
        ]);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('imaging_study_identifier', ['system' => 'http://sys-ids.kemkes.go.id/acsn/' . $orgId, 'use' => 'official']);
    }
}
