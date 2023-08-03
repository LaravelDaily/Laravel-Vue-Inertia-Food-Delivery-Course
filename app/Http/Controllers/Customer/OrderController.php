<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService)
    {
    }

    public function index(): Response
    {
        $this->authorize('order.viewAny');

        return Inertia::render('Customer/Orders', [
            'orders' => $this->orderService->getCustomerOrders(),
        ]);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $this->orderService->placeOrder(
            $request->user(),
            $request->validated()
        );

        return to_route('customer.orders.index')
            ->withStatus('Order accepted.');
    }
}
