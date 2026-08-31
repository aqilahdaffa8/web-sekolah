<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    protected $model = ClassRoom::class;

    public function definition(): array
    {
        return [
            'class_name' => fake()->unique()->bothify('?? ##'),
            'program_id' => Program::factory(),
        ];
    }
}
