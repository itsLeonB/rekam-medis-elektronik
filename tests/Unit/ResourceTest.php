<?php

namespace Tests\Unit;

use App\Models\FhirResource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index()
    {
        $user = User::factory()->create();
        $resType = 'Patient';

        $response = $this->actingAs($user)->get(route('resources.index', $resType));

        $response->assertStatus(200);
        $this->assertNotNull($response->json());
    }

    public function test_index_invalid_resource_type()
    {
        $user = User::factory()->create();
        $resType = 'InvalidResourceType';

        $response = $this->actingAs($user)->get(route('resources.index', $resType));

        $response->assertStatus(400);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $resType = 'Patient';
        $data = [
            'resourceType' => 'Patient',
            'id' => '100000030009',
        ];

        $response = $this->actingAs($user)->post(route('resources.store', $resType), $data);

        $response->assertStatus(201);
        $this->assertNotNull($response->json());
    }

    public function test_store_invalid_resource_type()
    {
        $user = User::factory()->create();
        $resType = 'InvalidResourceType';
        $data = [
            // Provide the necessary data for creating a new resource
        ];

        $response = $this->actingAs($user)->post(route('resources.store', $resType), $data);

        $response->assertStatus(400);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $resType = 'Patient';
        $resource = FhirResource::factory()->specific($resType)->create();

        $response = $this->actingAs($user)->get(route('resources.show', [$resType, $resource->id]));

        $response->assertStatus(200);
        $this->assertNotNull($response->json());
    }

    public function test_show_invalid_resource_type()
    {
        $user = User::factory()->create();
        $resType = 'InvalidResourceType';
        $resource = FhirResource::factory()->specific($resType)->create();

        $response = $this->actingAs($user)->get(route('resources.show', [$resType, $resource->id]));

        $response->assertStatus(400);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_show_invalid_resource_id()
    {
        $user = User::factory()->create();
        $resType = 'Patient';
        $invalidId = 0;

        $response = $this->actingAs($user)->get(route('resources.show', [$resType, $invalidId]));

        $response->assertStatus(404);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $resType = 'Patient';
        $resource = FhirResource::factory()->specific($resType)->create();
        $data = [
            'resourceType' => 'Patient',
            'id' => '100000030009',
            'status' => 'inactive',
        ];

        $response = $this->actingAs($user)->put(route('resources.update', [$resType, $resource->id]), $data);

        $response->assertStatus(200);
        $this->assertNotNull($response->json());
    }

    public function test_update_invalid_resource_type()
    {
        $user = User::factory()->create();
        $resType = 'InvalidResourceType';
        $resource = FhirResource::factory()->specific($resType)->create();
        $data = [
            // Provide the necessary data for updating the resource
        ];

        $response = $this->actingAs($user)->put(route('resources.update', [$resType, $resource->id]), $data);

        $response->assertStatus(400);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_update_invalid_resource_id()
    {
        $user = User::factory()->create();
        $resType = 'Patient';
        $invalidId = 0;
        $data = [
            // Provide the necessary data for updating the resource
        ];

        $response = $this->actingAs($user)->put(route('resources.update', [$resType, $invalidId]), $data);

        $response->assertStatus(404);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $resType = 'Patient';
        $resource = FhirResource::factory()->specific($resType)->create();

        $response = $this->actingAs($user)->delete(route('resources.destroy', [$resType, $resource->id]));

        $response->assertStatus(204);
        $this->assertNull($response->json());
    }

    public function test_destroy_invalid_resource_type()
    {
        $user = User::factory()->create();
        $resType = 'InvalidResourceType';
        $resource = FhirResource::factory()->specific($resType)->create();

        $response = $this->actingAs($user)->delete(route('resources.destroy', [$resType, $resource->id]));

        $response->assertStatus(400);
        $this->assertArrayHasKey('error', $response->json());
    }

    public function test_destroy_invalid_resource_id()
    {
        $user = User::factory()->create();
        $resType = 'Patient';
        $invalidId = 0;

        $response = $this->actingAs($user)->delete(route('resources.destroy', [$resType, $invalidId]));

        $response->assertStatus(404);
        $this->assertArrayHasKey('error', $response->json());
    }
}
