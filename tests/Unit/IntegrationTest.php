<?php

namespace Tests\Unit;

use App\Fhir\Satusehat;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\SatusehatController;
use App\Models\Fhir\Resource;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\FhirTest;

class IntegrationTest extends TestCase
{
    use DatabaseTransactions;
    use FhirTest;

    public function test_check_if_resource_exists_in_local_true()
    {
        $resource = Resource::factory()->create();

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->checkIfResourceExistsInLocal($resource->res_type, $resource->satusehat_id);

        $this->assertTrue($response);
    }

    public function test_check_if_resource_exists_in_local_false()
    {
        $resourceTypes = array_keys(Satusehat::AVAILABLE_METHODS);
        $resourceType = $resourceTypes[array_rand($resourceTypes)];

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->checkIfResourceExistsInLocal($resourceType, fake()->uuid());

        $this->assertFalse($response);
    }

    public function test_update_resource_if_newer()
    {
        $data = $this->getExampleData('organization');
        $data['id'] = '5fe612fe-eb92-4034-9337-7ad60ab15b94';

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $response = $this->json('POST', route('organization.store'), $data, $headers);

        $data['meta']['lastUpdated'] = now()->addDay()->toDateTimeString();

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->updateResourceIfNewer('organization', '5fe612fe-eb92-4034-9337-7ad60ab15b94', $data);

        $this->assertDatabaseCount('resource_content', 2);
    }

    public function test_do_not_update_resource_if_not_newer()
    {
        $resource = Resource::factory()->create();

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->updateResourceIfNewer($resource->res_type, $resource->satusehat_id, [
            'resourceType' => $resource->res_type,
            'id' => $resource->satusehat_id,
            'meta' => [
                'lastUpdated' => now()->subDay()->toDateTimeString(),
            ],
        ]);

        $this->assertDatabaseHas('resource', [
            'res_type' => $resource->res_type,
            'satusehat_id' => $resource->satusehat_id,
        ]);
        $this->assertIsArray($response);
    }

    public function test_get_resource_does_not_exist()
    {
        $resourceTypes = array_keys(Satusehat::AVAILABLE_METHODS);
        $resourceType = $resourceTypes[array_rand($resourceTypes)];

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->show($resourceType, fake()->uuid());

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_get_resource_newer_from_local()
    {
        $data = $this->getExampleData('organization');
        $data['id'] = '5fe612fe-eb92-4034-9337-7ad60ab15b94';
        unset($data['meta']);

        $headers = [
            'Content-Type' => 'application/json'
        ];
        $this->json('POST', route('organization.store'), $data, $headers);

        $this->put(
            route('satusehat.update', ['res_type' => 'organization', 'res_id' => '5fe612fe-eb92-4034-9337-7ad60ab15b94']),
            $data
        );

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->show('organization', '5fe612fe-eb92-4034-9337-7ad60ab15b94');

        $this->assertDatabaseCount('resource_content', 2);
    }
}
