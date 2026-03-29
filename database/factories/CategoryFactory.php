<?php

namespace Database\Factories;

class CategoryFactory
{
    public function definition(): array {
        return [
            'name' => fake()->unique()->word(),
            'color' => fake()->hexColor(),
        ];
    }

}
