<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    public function definition(): array
    {
        $cities = City::pluck('id');

        return [
            'city_id' => $cities->random(),
            'name'    => fake()->company(),
            'address' => fake()->address(),
        ];
    }
}
