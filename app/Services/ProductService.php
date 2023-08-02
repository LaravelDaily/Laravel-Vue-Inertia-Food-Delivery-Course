<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function createProduct(array $attributes): Product
    {
        return Product::create($attributes);
    }

    public function updateProduct(Product $product, array $attributes): Product
    {
        $product->update($attributes);

        return $product;
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }
}
