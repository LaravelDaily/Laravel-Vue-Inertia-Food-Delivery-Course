<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Models\City;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $this->authorize('restaurant.viewAny');

        return Inertia::render('Admin/Restaurants/Index', [
            'restaurants' => Restaurant::with(['city', 'owner'])->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('restaurant.create');

        return Inertia::render('Admin/Restaurants/Create', [
            'cities' => City::get(['id', 'name']),
        ]);
    }

    public function store(StoreRestaurantRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['owner_name'],
                'email'    => $validated['email'],
                'password' => '',
            ]);

            $user->roles()->sync(Role::where('name', RoleName::VENDOR->value)->first());

            $user->restaurant()->create([
                'city_id' => $validated['city_id'],
                'name'    => $validated['restaurant_name'],
                'address' => $validated['address'],
            ]);
        });

        return to_route('admin.restaurants.index');
    }

    public function edit(Restaurant $restaurant): Response
    {
        $this->authorize('restaurant.update');

        $restaurant->load(['city', 'owner']);

        return Inertia::render('Admin/Restaurants/Edit', [
            'restaurant' => $restaurant,
            'cities'     => City::get(['id', 'name']),
        ]);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): RedirectResponse
    {
        $validated = $request->validated();

        $restaurant->update([
            'city_id' => $validated['city_id'],
            'name'    => $validated['restaurant_name'],
            'address' => $validated['address'],
        ]);

        return to_route('admin.restaurants.index')
            ->withStatus('Restaurant updated successfully.');
    }
}
