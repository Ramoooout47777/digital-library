<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        $price = fake()->randomFloat(2, 5, 50);

        return [
            'category_id' => Category::factory(),
            'author_id' => Author::factory(),
            'publisher_id' => Publisher::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(6),
            'description' => fake()->paragraphs(2, true),
            'isbn' => fake()->unique()->numerify('#############'),
            'language' => 'km',
            'pages' => fake()->numberBetween(20, 500),
            'file_size' => fake()->numberBetween(500, 5000),
            'price' => $price,
            'discount' => 0,
            'final_price' => $price,
            'stock' => fake()->numberBetween(1, 20),
            'is_free' => false,
            'is_featured' => false,
            'status' => true,
            'published_at' => now()->subDay(),
        ];
    }
}
