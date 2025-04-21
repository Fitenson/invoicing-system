<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Modules\Project\Model\Project;
use App\Modules\User\Model\User;
use Illuminate\Support\Facades\Hash;

class CreateProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_project_creation_can_be_created()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'test@example.com',
            'password' => Hash::make('88888888')
        ]);

        $data = [
            'name' => 'New Website',
            'client' => $user->id,
            'description' => 'A new corporate website.',
            'rate_per_hour' => "100",
            'total_hours' => "40",
        ];

        $response = $this->post('/project/store', $data);

        $response->assertStatus(302);
        $this->assertDatabaseHas('projects', $data);
    }

    /** @test */
    public function test_project_creation_with_empty_data()
    {
        $this->withoutMiddleware();

        $response = $this->post('/project/store', []);

        $response->assertStatus(302);

        $this->assertDatabaseCount('projects', 1);

        $this->assertDatabaseHas('projects', [
            'name' => null,
            'client' => null,
            'description' => null,
            'rate_per_hour' => null,
            'total_hours' => null,
        ]);
    }
}
