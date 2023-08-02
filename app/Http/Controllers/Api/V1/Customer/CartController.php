<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        $cart = session('cart', [
            'items'           => [],
            'total'           => 0,
            'restaurant_name' => '',
            'restaurant_id'   => '',
        ]);

        return response()->json($cart);
    }

    public function add(Product $product): JsonResponse
    {
        $this->authorize('cart.add');

        $restaurant = $product->category->restaurant;

        $cart = session('cart', [
            'items'           => [],
            'total'           => 0,
            'restaurant_name' => '',
            'restaurant_id'   => '',
        ]);

        $validator = Validator::make($cart, [
            'items'                 => ['array'],
            'items.*.restaurant_id' => ['required', 'in:' . $restaurant->id],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Can\'t add product from different vendor.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $item                  = $product->toArray();
        $item['uuid']          = (string) str()->uuid();
        $item['restaurant_id'] = $restaurant->id;

        session()->push('cart.items', $item);
        session()->put('cart.restaurant_name', $restaurant->name);
        session()->put('cart.restaurant_id', $restaurant->id);

        $this->updateTotal();

        return response()->json(session('cart'), Response::HTTP_ACCEPTED);
    }

    public function remove($uuid): JsonResponse
    {
        $items = collect(session('cart.items'))
            ->reject(function ($item) use ($uuid) {
                return $item['uuid'] == $uuid;
            });

        session(['cart.items' => $items->values()->toArray()]);

        $this->updateTotal();

        return response()->json(session('cart'), Response::HTTP_ACCEPTED);
    }

    public function destroy(): Response
    {
        session()->forget('cart');

        return response()->noContent();
    }

    protected function updateTotal(): void
    {
        $items = collect(session('cart.items'));

        session()->put('cart.total', $items->sum('price'));
    }
}
