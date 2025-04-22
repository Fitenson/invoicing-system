<?php

namespace App\Modules\Project\Factory;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use App\Modules\Project\Model\Project;


class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'rate_per_hour' => $this->faker->randomFloat(2, 5, 100),
            'total_hours' => $this->faker->numberBetween(10, 300),
        ];
    }
}
