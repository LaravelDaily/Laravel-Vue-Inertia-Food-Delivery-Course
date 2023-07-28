<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Http\Resources\Api\V1\Admin\RestaurantCollection;
use App\Http\Resources\Api\V1\Admin\RestaurantResource;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RestaurantController extends Controller
{
    public function __construct(public RestaurantService $restaurantService)
    {
    }

    public function index(): RestaurantCollection
    {
        $this->authorize('restaurant.viewAny');

        $restaurants = Restaurant::with(['city', 'owner'])->get();

        return new RestaurantCollection($restaurants);
    }

    public function store(StoreRestaurantRequest $request): RestaurantResource
    {
        $restaurant = $this->restaurantService->createRestaurant(
            $request->validated()
        );

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
        $restaurant = $this->restaurantService->updateRestaurant(
            $restaurant,
            $request->validated()
        );

        return (new RestaurantResource($restaurant))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
