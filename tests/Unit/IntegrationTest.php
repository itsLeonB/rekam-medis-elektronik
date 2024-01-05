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
        $this->json('POST', route('organization.store'), $data, $headers);

        $data['meta']['lastUpdated'] = now()->addDay()->toDateTimeString();

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->updateResourceIfNewer('organization', '5fe612fe-eb92-4034-9337-7ad60ab15b94', $data);

        $this->assertEquals(200, $response->getStatusCode());
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

        $response = $this->json('GET', route('integration.show', ['res_type' => $resourceType, 'satusehat_id' => fake()->uuid()]));

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_get_resource_exist_in_local()
    {
        $data = $this->getExampleData('Organization');
        $data['id'] = fake()->uuid();
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $this->json('POST', route('organization.store'), $data, $headers);

        $response = $this->json('GET', route('integration.show', ['res_type' => 'organization', 'satusehat_id' => $data['id']]));

        $this->assertEquals(200, $response->getStatusCode());
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
            route('satusehat.resource.update', ['res_type' => 'organization', 'res_id' => '5fe612fe-eb92-4034-9337-7ad60ab15b94']),
            $data
        );

        $response = $this->json('GET', route('integration.show', ['res_type' => 'Organization', 'satusehat_id' => '5fe612fe-eb92-4034-9337-7ad60ab15b94']));

        $response->assertSuccessful();
        $this->assertDatabaseCount('resource_content', 2);
    }

    public function test_get_new_data()
    {
        $response = $this->json('GET', route('integration.show', ['res_type' => 'Organization', 'satusehat_id' => '5fe612fe-eb92-4034-9337-7ad60ab15b94']));

        $response->assertSuccessful();
        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('organization', 1);
    }

    public function test_post_new_data()
    {
        $data = $this->getExampleData('Organization');
        $data['name'] = fake()->streetName();
        unset($data['id']);
        unset($data['identifier']);

        $response = $this->json(
            'POST',
            route('integration.store', ['res_type' => 'Organization']),
            $data
        );

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta' => [
                'lastUpdated',
            ],
        ]);
        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 1);
        $this->assertDatabaseCount('organization', 1);
    }

    public function test_update_data()
    {
        $data = $this->getExampleData('Organization');
        $data['name'] = fake()->streetName();
        $data['id'] = '5fe612fe-eb92-4034-9337-7ad60ab15b94';
        unset($data['identifier']);

        $this->json(
            'POST',
            route('organization.store'),
            $data
        );

        $response = $this->json(
            'PUT',
            route('integration.update', ['res_type' => 'Organization', 'satusehat_id' => $data['id']]),
            $data
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta' => [
                'lastUpdated',
            ],
        ]);
        $this->assertDatabaseCount('resource', 1);
        $this->assertDatabaseCount('resource_content', 2);
        $this->assertDatabaseCount('organization', 1);
    }
}
