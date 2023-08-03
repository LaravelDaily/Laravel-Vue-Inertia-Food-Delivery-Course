<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(public CartService $cart)
    {
    }

    public function index(): Response
    {
        return Inertia::render('Customer/Cart');
    }

    public function add(Product $product): RedirectResponse
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
            return back()->withErrors(['message' => 'Can\'t add product from different vendor.']);
        }

        $this->cart->addItem($product);

        return back();
    }

    public function remove(string $uuid)
    {
        abort_if(! $this->cart->removeItem($uuid), HttpResponse::HTTP_NOT_FOUND);

        return back();
    }

    public function destroy()
    {
        $this->cart->flush();

        return back();
    }
}
