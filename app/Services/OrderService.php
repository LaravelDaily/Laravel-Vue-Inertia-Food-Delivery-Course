<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderCreated;
use App\Services\CartService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        public CartService $cart
    ) {
    }

    public function getCustomerOrders(): Collection
    {
        return Order::with(['restaurant', 'products'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();
    }

    public function placeOrder(User $user, array $attributes): Order
    {
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

        return $order;
    }

    public function getOrders(?string $period = null): Collection
    {
        $query = Order::query()->with(['customer', 'products'])
            ->where('restaurant_id', auth()->user()->restaurant_id);

        match ($period) {
            'current' => $query->current()->latest(),
            'past'    => $query->past()->latest('updated_at'),
            default   => $query->latest(),
        };

        return $query->get();
    }

    public function getCurrentOrders(): Collection
    {
        return $this->getOrders('current');
    }

    public function getPastOrders(): Collection
    {
        return $this->getOrders('past');
    }

    public function updateOrder(Order $order, array $attributes): Order
    {
        $order->update($attributes);

        return $order;
    }
}
