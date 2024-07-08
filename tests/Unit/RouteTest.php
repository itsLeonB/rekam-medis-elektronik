<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_route(): void
    {
        $response = $this->get('/profile');

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function test_auth_route(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/profile');

        $response->assertStatus(200);
    }
}
