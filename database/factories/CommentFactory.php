<?php

namespace Database\Factories;

class CommentFactory
{
    public function definition(): array {
        return [
            'body' => fake()->sentence(10),
        ];
    }

}
