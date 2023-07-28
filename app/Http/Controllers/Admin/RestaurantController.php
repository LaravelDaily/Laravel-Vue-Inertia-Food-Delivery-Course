<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Models\City;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RestaurantController extends Controller
{
    public function __construct(public RestaurantService $restaurantService)
    {
    }

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
        $this->restaurantService->createRestaurant(
            $request->validated()
        );

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
        $this->restaurantService->updateRestaurant(
            $restaurant,
            $request->validated()
        );

        return to_route('admin.restaurants.index')
            ->withStatus('Restaurant updated successfully.');
    }
}
