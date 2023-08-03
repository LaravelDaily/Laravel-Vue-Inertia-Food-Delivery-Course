<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Http\Resources\Api\V1\Customer\OrderCollection;
use App\Http\Resources\Api\V1\Customer\OrderResource;
use App\Models\Order;
use App\Notifications\NewOrderCreated;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(public CartService $cart)
    {
    }

    public function index(): OrderCollection
    {
        $this->authorize('order.viewAny');

        $orders = Order::with(['restaurant', 'products'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return new OrderCollection($orders);
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $user       = $request->user();
        $attributes = $request->validated();

        $order = DB::transaction(function () use ($user, $attributes) {
            $order = $user->orders()->create([
                'restaurant_id' => $attributes['restaurant_id'],
                'total'         => $attributes['total'],
                'status'        => OrderStatus::PENDING,
            ]);

            $order->products()->createMany($attributes['items']);

            return $order;
        });

        $order->restaurant->owner->notify(new NewOrderCreated($order));

        $this->cart->flush();

        return new OrderResource($order);
    }
}
