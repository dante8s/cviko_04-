<?php

namespace Database\Factories;

class NoteFactory
{
    public function definition(): array {
        return [
            'title'     => fake()->sentence(),
            'body'      => fake()->paragraphs(3, true),
            'status'    => fake()->randomElement(['draft', 'published', 'archived']),
            'is_pinned' => fake()->boolean(15), // cca 15% pinned
        ];
    }
}
