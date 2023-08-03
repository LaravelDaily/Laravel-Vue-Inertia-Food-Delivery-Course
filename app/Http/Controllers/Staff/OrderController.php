<?php

namespace App\Http\Controllers\Staff;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Staff/Orders', [
            'current_orders' => $this->orderService->getCurrentOrders(),
            'past_orders'    => $this->orderService->getPastOrders(),
            'order_status'   => OrderStatus::toArray(),
        ]);
    }

    public function update(UpdateOrderRequest $request, $orderId)
    {
        $order = Order::where('restaurant_id', $request->user()->restaurant_id)
            ->findOrFail($orderId);

        $this->orderService->updateOrder($order, $request->validated());

        return back();
    }
}
