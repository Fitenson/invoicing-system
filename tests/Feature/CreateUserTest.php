<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Modules\User\Model\User;

class CreateUserTest extends TestCase
{
    use RefreshDatabase; // resets the DB between tests

    /**
     * Test creating a user.
     */
    public function test_user_can_be_created(): void
    {
        $this->withoutMiddleware();

        // Sample user data
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password', // note: not hashed here
            'password_confirmation' => 'password',
        ];

        // Simulate form POST request
        $response = $this->post('/user/store', $data);

        $response->assertRedirect('/user');

        // Assert the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /**
     * Test POST empty data when creating a user.
     */
    public function test_user_creation_fails_with_missing_fields(): void
    {
        $this->withoutMiddleware();

        $response = $this->post('/user/store', []); // No data sent

        $response->assertSessionHasErrors(['name', 'email']);
    }


    /**
     * Test POST duplicate email when creating a user.
     */
    public function test_user_creation_fails_with_duplicate_email(): void
    {
        $this->withoutMiddleware();

        // Create a user first
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        // Try to create a user with the same email
        $response = $this->post('/user/store', [
            'name' => 'Jane Doe',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
