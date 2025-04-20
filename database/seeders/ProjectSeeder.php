<?php

namespace Database\Seeders;

use App\Modules\Project\Model\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            'project_1' => [
                'name' => 'Manhattan Project',
                'description' => 'The Manhattan Project was a top-secret U.S. research initiative during World War II that developed the world’s first nuclear weapons. Led by the United States with support from the United Kingdom and Canada, it resulted in the atomic bombs dropped on Hiroshima and Nagasaki in 1945.',
                'rate_per_hour' => '8.5',
                'total_hours' => '80'
            ],

            'project_2' => [
                'name' => 'Apollo Project',
                'description' => 'The Apollo Project was a NASA-led space program during the 1960s and 1970s aimed at landing humans on the Moon and bringing them safely back to Earth. It successfully achieved its goal with the historic Apollo 11 mission in 1969, when Neil Armstrong became the first person to walk on the Moon.',
                'rate_per_hour' => '9.5',
                'total_hours' => '100'
            ],

            'project_3' => [
                'name' => 'Aurora Project',
                'description' => 'The Aurora Project is a rumored top-secret U.S. military program that allegedly involves the development of advanced, high-speed aircraft, possibly hypersonic or spaceplanes. While there has never been official confirmation of its existence, speculation about Aurora began in the 1980s, fueled by unusual sightings and classified budget items. The project remains part of aviation and conspiracy lore.',
                'rate_per_hour' => '7.5',
                'total_hours' => '60'
            ],

            'project_4' => [
                'name' => 'Stargate Project',
                'description' => 'The **Stargate Project** was a secret U.S. Army initiative launched during the Cold War to investigate the potential of psychic phenomena for military and intelligence purposes—particularly **remote viewing**, the ability to perceive distant or unseen targets using extrasensory perception (ESP). Active from the 1970s until the mid-1990s, the program was eventually declassified and discontinued after evaluations deemed the results too inconsistent for practical use.',
                'rate_per_hour' => '9.5',
                'total_hours' => '75'
            ]
        ];

        foreach($projects as $project) {
            Project::create($project);
        }
    }
}
