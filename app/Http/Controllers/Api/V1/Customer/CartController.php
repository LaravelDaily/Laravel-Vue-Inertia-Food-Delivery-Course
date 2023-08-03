<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct(public CartService $cart)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->cart->all());
    }

    public function add(Product $product): JsonResponse
    {
        $this->authorize('cart.add');

        $validator = Validator::make($this->cart->all(), [
            'items'                 => ['array'],
            'items.*.restaurant_id' => [
                'required',
                'in:' . $product->category->restaurant->id,
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Can\'t add product from different vendor.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->cart->addItem($product);

        return response()->json($this->cart->all(), Response::HTTP_ACCEPTED);
    }

    public function remove($uuid): JsonResponse
    {
        abort_if(! $this->cart->removeItem($uuid), Response::HTTP_NOT_FOUND);

        return response()->json($this->cart->all(), Response::HTTP_ACCEPTED);
    }

    public function destroy(): Response
    {
        $this->cart->flush();

        return response()->noContent();
    }
}
