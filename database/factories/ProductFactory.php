<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'details' => fake()->paragraph(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL', 'One Size']),
            'color' => fake()->colorName(),
            'category' => fake()->randomElement(['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports', 'Food & Grocery']),
            'price' => fake()->randomFloat(2, 10, 500),
            'status' => fake()->randomElement(['active', 'inactive']),
            'image' => null,
        ];
    }
}