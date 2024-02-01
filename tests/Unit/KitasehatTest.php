<?php

namespace Tests\Unit;

use Tests\TestCase;

class KitasehatTest extends TestCase
{
    public function test_post_patient(): void
    {
        $data = [
            'patient' => [
                'nik' => '9271060312000001'
            ]
        ];

        $response = $this->postJson('/api/kitasehat/patient', $data);

        $response->assertStatus(200);
    }
}
