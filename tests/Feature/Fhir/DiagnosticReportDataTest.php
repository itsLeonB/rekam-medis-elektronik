<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class DiagnosticReportDataTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    /**
     * Test apakah user dapat menlihat data laporan hasil pemeriksaan lab
     */
    public function test_users_can_view_diagnostic_report_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('diagnosticreport');

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/diagnosticreport', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $response = $this->json('GET', '/api/diagnosticreport/' . $newData['resource_id']);
        $response->assertStatus(200);
    }


    /**
     * Test apakah user dapat membuat data laporan hasil pemeriksaan lab baru
     */
    public function test_users_can_create_new_diagnostic_report_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('diagnosticreport');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/diagnosticreport', $data, $headers);
        $response->assertStatus(201);

        $this->assertMainData('diagnostic_report', $data['diagnostic']);
        $this->assertManyData('diagnostic_report_media', $data['media']);
        $this->assertManyData('diagnostic_report_conclusion_code', $data['conclusionCode']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('diagnostic_report_identifier', ['system' => 'http://sys-ids.kemkes.go.id/diagnostic/' . $orgId . '/lab', 'use' => 'official']);
    }


    /**
     * Test apakah user dapat memperbarui data laporan hasil pemeriksaan lab
     */
    public function test_users_can_update_diagnostic_report_data()
    {
        Config::set('app.organization_id', env('organization_id'));

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = $this->getExampleData('diagnosticreport');
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', '/api/diagnosticreport', $data, $headers);
        $newData = json_decode($response->getContent(), true);

        $data['diagnosticreport']['id'] = $newData['id'];
        $data['diagnosticreport']['resource_id'] = $newData['resource_id'];
        $data['diagnosticreport']['status'] = 'cancelled';
        $response = $this->json('PUT', '/api/diagnosticreport/' . $newData['resource_id'], $data, $headers);
        $response->assertStatus(200);

        $this->assertMainData('diagnostic_report', $data['diagnostic']);
        $this->assertManyData('diagnostic_report_media', $data['media']);
        $this->assertManyData('diagnostic_report_conclusion_code', $data['conclusionCode']);
        $orgId = env('organization_id');
        $this->assertDatabaseHas('diagnostic_report_identifier', ['system' => 'http://sys-ids.kemkes.go.id/diagnostic/' . $orgId . '/lab', 'use' => 'official']);
    }
}
