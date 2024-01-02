<?php

namespace Tests\Unit;

use App\Fhir\Satusehat;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\SatusehatController;
use App\Models\Fhir\Resource;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use DatabaseTransactions;

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
        $resource = Resource::factory()->create();

        $controller = new IntegrationController(new SatusehatController());

        $response = $controller->updateResourceIfNewer($resource->res_type, $resource->satusehat_id, [
            'resourceType' => $resource->res_type,
            'id' => $resource->satusehat_id,
            'meta' => [
                'lastUpdated' => now()->addDay()->toDateTimeString(),
            ],
        ]);

        $this->assertDatabaseHas('resource', [
            'res_type' => $resource->res_type,
            'satusehat_id' => $resource->satusehat_id,
        ]);
        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);

        dd($resource->loadCount('content'));
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

        $response = $controller->get($resourceType, fake()->uuid());

        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_get_resource_newer_from_local()
    {
        $resource = Resource::factory()->create([
            'res_type' => 'Organization',
            'satusehat_id' => '5fe612fe-eb92-4034-9337-7ad60ab15b94',
        ]);

        $satusehatController = new SatusehatController();
        Http::put(route('satusehat.put', ['res_type' => $resource->res_type, 'res_id' => $resource->satusehat_id]), [
            'resourceType' => $resource->res_type,
            'id' => $resource->satusehat_id,
        ]);

        $controller = new IntegrationController($satusehatController);

        $response = $controller->get($resource->res_type, $resource->satusehat_id);

        $this->assertInstanceOf(\Illuminate\Http\Client\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
