<?php

namespace Tests\Unit;

use App\Models\Fhir\Resources\Patient;
use App\Models\User;
use Tests\TestCase;

class ResourceTest extends TestCase
{
    public function test_index(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $count = fake()->randomDigitNotZero();
        Patient::factory()->count($count)->create();

        $response = $this->actingAs($user)->get(route('local.resource.index', ['res_type' => 'patient']));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'satusehat_id',
                'res_type',
                'res_version',
                'fhir_ver',
                'created_at',
                'updated_at'
            ]
        ]);
        $response->assertJsonCount($count);
    }
}
