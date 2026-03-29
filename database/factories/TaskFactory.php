<?php

namespace Database\Factories;

class TaskFactory
{
    public function definition(): array {
        return [
            'title'   => fake()->sentence(3),
            'is_done' => fake()->boolean(30),
            'due_at'  => fake()->optional()->dateTimeBetween('now', '+14 days'),
        ];
    }

}
