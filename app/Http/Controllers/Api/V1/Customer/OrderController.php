<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Http\Resources\Api\V1\Customer\OrderCollection;
use App\Http\Resources\Api\V1\Customer\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
    }

    public function index(): OrderCollection
    {
        $this->authorize('order.viewAny');

        return new OrderCollection(
            $this->orderService->getCustomerOrders()
        );
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $order = $this->orderService->placeOrder(
            $request->user(),
            $request->validated()
        );

        return new OrderResource($order);
    }
}
