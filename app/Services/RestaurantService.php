<?php

namespace App\Services;

use App\Enums\RoleName;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use App\Notifications\RestaurantOwnerInvitation;
use Illuminate\Support\Facades\DB;

class RestaurantService
{
    public function createRestaurant(array $attributes): Restaurant
    {
        return DB::transaction(function () use ($attributes) {
            $user = User::create([
                'name'     => $attributes['owner_name'],
                'email'    => $attributes['email'],
                'password' => '',
            ]);

            $user->roles()->sync(Role::where('name', RoleName::VENDOR->value)->first());

            $restaurant = $user->restaurant()->create([
                'city_id' => $attributes['city_id'],
                'name'    => $attributes['restaurant_name'],
                'address' => $attributes['address'],
            ]);

            $user->notify(new RestaurantOwnerInvitation($attributes['restaurant_name']));

            return $restaurant;
        });
    }

    public function updateRestaurant(Restaurant $restaurant, array $attributes): Restaurant
    {
        $restaurant->update([
            'city_id' => $attributes['city_id'],
            'name'    => $attributes['restaurant_name'],
            'address' => $attributes['address'],
        ]);

        return $restaurant;
    }
}
