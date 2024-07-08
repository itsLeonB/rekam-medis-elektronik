<?php

namespace Tests\Unit;

use App\Models\FhirResource;
use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index_users()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        // Create some users
        User::factory()->count(3)->unverified()->create();

        // Send a GET request to the index method
        $response = $this->actingAs($admin)->get(route('users.index'));

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the users data
        $response->assertJson(['users' => User::paginate(15)->withQueryString()->toArray()]);
    }

    public function test_index_user_with_query()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create some users
        $users = User::factory()->count(3)->unverified()->create();

        // Send a GET request to the index method with query
        $response = $this->actingAs($admin)->get(route('users.index', ['name' => $users[0]->name]));

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the users data
        $response->assertJson([
            'users' => User::where('name', 'like', "%{$users[0]->name}%")
                ->paginate(15)
                ->withQueryString()
                ->toArray()
        ]);
    }

    public function test_show_user()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a user
        $user = User::factory()->create();

        // Send a GET request to the show method with the user id
        $response = $this->actingAs($admin)->get(route('users.show', ['user_id' => $user->id]));

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the user data
        $response->assertJson($user->toArray());
    }

    public function test_create_new_user()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a new user data
        $user = User::factory()->unverified()->make();
        $userData = $user->toArray();
        $password = fake()->password(8);
        $userData['password'] = $password;
        $userData['password_confirmation'] = $password;
        $userData['role'] = 'perekammedis';

        $practitioner = FhirResource::factory()->specific('Practitioner')->create();
        $userData['practitioner_id'] = $practitioner->id;

        // Send a POST request to the store method with the user data
        $response = $this->actingAs($admin)->post(route('users.store'), $userData);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response contains the created user data
        $response->assertJsonFragment(['name' => $userData['name']]);
        $response->assertJsonFragment(['email' => $userData['email']]);
    }

    public function test_create_user_non_practitioner()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a new user data
        $user = User::factory()->unverified()->make();
        $userData = $user->toArray();
        $password = fake()->password(8);
        $userData['password'] = $password;
        $userData['password_confirmation'] = $password;
        $userData['role'] = 'admin';

        // Send a POST request to the store method with the user data
        $response = $this->actingAs($admin)->post(route('users.store'), $userData);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert that the response contains the created user data
        $response->assertJsonFragment(['name' => $userData['name']]);
        $response->assertJsonFragment(['email' => $userData['email']]);
    }

    public function test_update_user_same_email()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a user
        $user = User::factory()->create();

        $password = fake()->password(8);

        // Create updated user data
        $updatedUserData = [
            'name' => fake()->name(),
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
            'role' => 'perekammedis'
        ];

        // Send a PUT request to the update method with the user id and updated user data
        $response = $this->actingAs($admin)->put(route('users.update', ['user_id' => $user->id]), $updatedUserData);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        $user->refresh();

        $passwordTimestamp = $user->password_changed_at;

        // Assert that the response contains the updated user data
        $response->assertJsonFragment(['name' => $updatedUserData['name']]);
        $response->assertJsonFragment(['email' => $updatedUserData['email']]);
        $response->assertJsonFragment(['email_verified_at' => null]);
        $this->assertNotNull($passwordTimestamp);
    }

    public function test_update_user_diff_email()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a user
        $user = User::factory()->create();

        $password = fake()->password(8);

        // Create updated user data
        $updatedUserData = [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => $password,
            'password_confirmation' => $password,
            'role' => 'perekammedis'
        ];

        // Send a PUT request to the update method with the user id and updated user data
        $response = $this->actingAs($admin)->put(route('users.update', ['user_id' => $user->id]), $updatedUserData);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        $user->refresh();

        $passwordTimestamp = $user->password_changed_at;

        // Assert that the response contains the updated user data
        $response->assertJsonFragment(['name' => $updatedUserData['name']]);
        $response->assertJsonFragment(['email' => $updatedUserData['email']]);
        $response->assertJsonFragment(['email_verified_at' => null]);
        $this->assertNotNull($passwordTimestamp);
    }

    public function test_delete_user()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create a user
        $user = User::factory()->create();

        // Send a DELETE request to the delete method with the user id
        $response = $this->actingAs($admin)->delete(route('users.destroy', ['user_id' => $user->id]));

        // Assert that the response has a 204 status code
        $response->assertStatus(204);

        // Assert that the user is deleted from the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_delete_self()
    {
        Role::create(['name' => 'admin']);
        // Create a user
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Send a DELETE request to the delete method with the user id
        $response = $this->actingAs($admin)->delete(route('users.destroy', ['user_id' => $admin->id]));

        $response->assertStatus(403);
    }
}
