<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'  => fake()->words(3, true),
            'price' => rand(499, 5999),
        ];
    }
}
