<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Models\Order;
use App\Notifications\NewOrderCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        $this->authorize('order.viewAny');

        $orders = Order::with(['restaurant', 'products'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return Inertia::render('Customer/Orders', [
            'orders' => $orders,
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
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

        session()->forget('cart');

        return to_route('customer.orders.index')
            ->withStatus('Order accepted.');
    }
}
