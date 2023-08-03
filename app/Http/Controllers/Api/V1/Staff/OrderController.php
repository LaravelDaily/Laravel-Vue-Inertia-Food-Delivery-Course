<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateOrderRequest;
use App\Http\Resources\Api\V1\Staff\OrderCollection;
use App\Http\Resources\Api\V1\Staff\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
    }

    public function index(): OrderCollection
    {
        return new OrderCollection($this->orderService->getCurrentOrders());
    }

    public function past(): OrderCollection
    {
        return new OrderCollection($this->orderService->getPastOrders());
    }

    public function update(UpdateOrderRequest $request, $orderId): JsonResponse
    {
        $order = Order::where('restaurant_id', $request->user()->restaurant_id)
            ->findOrFail($orderId);

        $order = $this->orderService->updateOrder($order, $request->validated());

        return (new OrderResource($order))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
