<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = collect([
            'Pizza',
            'Snacks',
            'Soups',
            'Desserts',
            'Kids menu',
            'Drinks',
            'Salads',
            'Chicken',
            'Duck',
            'Pork',
            'Beef',
            'Fish',
            'Pasta',
            'Burgers',
            'Dumplings',
            'Ramen',
        ]);

        return [
            'name' => $categories->random(),
        ];
    }
}
