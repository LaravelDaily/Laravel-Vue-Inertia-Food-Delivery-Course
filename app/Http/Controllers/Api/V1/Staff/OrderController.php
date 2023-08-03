<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateOrderRequest;
use App\Http\Resources\Api\V1\Staff\OrderCollection;
use App\Http\Resources\Api\V1\Staff\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(): OrderCollection
    {
        $orders = Order::current()
            ->with(['customer', 'products'])
            ->where('restaurant_id', auth()->user()->restaurant_id)
            ->latest()
            ->get();

        return new OrderCollection($orders);
    }

    public function past(): OrderCollection
    {
        $orders = Order::past()
            ->with(['customer', 'products'])
            ->where('restaurant_id', auth()->user()->restaurant_id)
            ->latest()
            ->get();

        return new OrderCollection($orders);
    }

    public function update(UpdateOrderRequest $request, $orderId): JsonResponse
    {
        $order = Order::where('restaurant_id', $request->user()->restaurant_id)
            ->findOrFail($orderId);

        $order->update($request->validated());

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
