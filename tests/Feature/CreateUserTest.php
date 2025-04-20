<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
}
