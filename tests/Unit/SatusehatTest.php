<?php

namespace Tests\Unit;

use App\Http\Controllers\SatusehatController;
use App\Models\Fhir\OrganizationIdentifier;
use Tests\TestCase;

class SatusehatTest extends TestCase
{
    public function test_get_auth_token(): void
    {
        $controller = new SatusehatController();
        $token = $controller->getToken();
        $this->assertIsString($token);
    }

    public function test_get_resource(): void
    {
        $resType = 'Practitioner';
        $resId = 'N10000001';

        $response = $this->get(route('satusehat.get', ['res_type' => $resType, 'res_id' => $resId]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta'
        ]);
    }

    public function test_post_resource(): void
    {
        $idUses = OrganizationIdentifier::USE['binding']['valueset']['code'];
        $idUse = $idUses[array_rand($idUses)];

        $dataArray = [
            'resourceType' => 'Organization',
            'active' => fake()->boolean(),
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/organization/1000079374',
                    'use' => $idUse,
                    'value' => fake()->uuid(),
                ],
            ],
            'name' => fake()->name(),
        ];

        $resType = $dataArray['resourceType'];

        $response = $this->post(route('satusehat.post', ['res_type' => $resType]), $dataArray);

        $response->assertStatus(200);
        $response->assertJsonFragment(['resourceType' => $resType]);
        $response->assertJsonFragment(['name' => $dataArray['name']]);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta'
        ]);
    }

    public function test_put_resource(): void
    {
        $idUses = OrganizationIdentifier::USE['binding']['valueset']['code'];
        $idUse = $idUses[array_rand($idUses)];

        $satusehatId = 'abddd50b-b22f-4d68-a1c3-d2c29a27698b';

        $dataArray = [
            'resourceType' => 'Organization',
            'id' => $satusehatId,
            'active' => fake()->boolean(),
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/organization/1000079374',
                    'use' => $idUse,
                    'value' => fake()->uuid(),
                ],
            ],
            'name' => fake()->name(),
        ];

        $resType = $dataArray['resourceType'];

        $response = $this->put(route('satusehat.put', ['res_type' => $resType, 'res_id' => $satusehatId]), $dataArray);

        $response->assertStatus(200);
        $response->assertJsonFragment(['resourceType' => $resType]);
        $response->assertJsonFragment(['id' => $dataArray['id']]);
        $response->assertJsonFragment(['name' => $dataArray['name']]);
        $response->assertJsonStructure([
            'resourceType',
            'id',
            'meta'
        ]);
    }
}
