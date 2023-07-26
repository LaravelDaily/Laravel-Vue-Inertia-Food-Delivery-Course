<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\RoleName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Http\Resources\Api\V1\Admin\RestaurantCollection;
use App\Http\Resources\Api\V1\Admin\RestaurantResource;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use App\Notifications\RestaurantOwnerInvitation;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(): RestaurantCollection
    {
        $this->authorize('restaurant.viewAny');

        $restaurants = Restaurant::with(['city', 'owner'])->get();

        return new RestaurantCollection($restaurants);
    }

    public function store(StoreRestaurantRequest $request): RestaurantResource
    {
        $validated = $request->validated();

        $restaurant = DB::transaction(function () use ($validated) {
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

            $user->notify(new RestaurantOwnerInvitation($validated['restaurant_name']));
        });

        return new RestaurantResource($restaurant);
    }

    public function show(Restaurant $restaurant): RestaurantResource
    {
        $this->authorize('restaurant.view');

        $restaurant->load(['city', 'owner']);

        return new RestaurantResource($restaurant);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();

        $restaurant->update([
            'city_id' => $validated['city_id'],
            'name'    => $validated['restaurant_name'],
            'address' => $validated['address'],
        ]);

        return (new RestaurantResource($restaurant))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
