<?php

namespace App\Http\Controllers\Staff;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateOrderRequest;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        $currentOrders = Order::current()
            ->with(['customer', 'products'])
            ->where('restaurant_id', auth()->user()->restaurant_id)
            ->latest()
            ->get();

        $pastOrders = Order::past()
            ->with(['customer', 'products'])
            ->where('restaurant_id', auth()->user()->restaurant_id)
            ->latest()
            ->get();

        return Inertia::render('Staff/Orders', [
            'current_orders' => $currentOrders,
            'past_orders'    => $pastOrders,
            'order_status'   => OrderStatus::toArray(),
        ]);
    }

    public function update(UpdateOrderRequest $request, $orderId)
    {
        $order = Order::where('restaurant_id', $request->user()->restaurant_id)
            ->findOrFail($orderId);

        $order->update($request->validated());

        return back();
    }
}
