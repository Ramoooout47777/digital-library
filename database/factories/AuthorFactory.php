<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'bio' => fake()->paragraph(),
            'email' => fake()->unique()->safeEmail(),
            'website' => fake()->url(),
            'status' => true,
        ];
    }
}
