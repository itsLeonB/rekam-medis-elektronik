<?php

namespace Tests\Feature;

use App\Models\FhirResource;
use App\Models\User;
use Faker\Factory;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $faker = Factory::create();
        $email = $faker->unique()->safeEmail;

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Testing User',
                'email' => $email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Testing User', $user->name);
        $this->assertSame($email, $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_email_verification_is_sent_on_email_change(): void
    {
        $user = User::factory()->create();

        Notification::fake();

        $response = $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'leonpcomputer@gmail.com',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertArrayHasKey('status', $response->getSession()->all());

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_practitioner_resource_returned_from_profile(): void
    {
        $user = User::factory()->create();
        $practitioner = FhirResource::factory()->specific('Practitioner')->create();
        $user->practitionerUser()->save($practitioner);

        $response = $this->actingAs($user)->get(route('profile.details'));

        $response->assertOk();
        $response->assertJsonFragment($practitioner->toArray());
    }

    public function test_practitioner_resource_not_returned_if_not_logged_in(): void
    {
        $user = User::factory()->create();
        $practitioner = FhirResource::factory()->specific('Practitioner')->create();
        $user->practitionerUser()->save($practitioner);

        $response = $this->get(route('profile.details'));

        $response->assertRedirect(route('login'));
    }
}
