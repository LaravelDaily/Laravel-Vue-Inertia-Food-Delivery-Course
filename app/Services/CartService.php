<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    public function all(): array
    {
        return session('cart', [
            'items'           => [],
            'total'           => 0,
            'restaurant_name' => '',
            'restaurant_id'   => '',
        ]);
    }

    public function items(): array
    {
        return $this->all()['items'];
    }

    public function total(): int
    {
        return (int) $this->all()['total'];
    }

    public function restaurantId(): int
    {
        return (int) $this->all()['restaurant_id'];
    }

    public function flush(): void
    {
        session()->forget('cart');
    }

    public function addItem(Product $product): void
    {
        $restaurant = $product->category->restaurant;

        $item                  = $product->toArray();
        $item['uuid']          = (string) str()->uuid();
        $item['restaurant_id'] = $restaurant->id;

        session()->push('cart.items', $item);
        session()->put('cart.restaurant_name', $restaurant->name);
        session()->put('cart.restaurant_id', $restaurant->id);

        $this->updateTotal();
    }

    public function removeItem(string $uuid = ''): bool
    {
        $items = collect($this->items());

        [$removed, $new] = $items->partition(fn ($item) => $item['uuid'] === $uuid);

        if (! count($removed)) {
            return false;
        }

        session(['cart.items' => $new->values()->toArray()]);

        $this->updateTotal();

        return true;
    }

    protected function updateTotal(): void
    {
        session()->put('cart.total', collect($this->items())->sum('price'));
    }
}
