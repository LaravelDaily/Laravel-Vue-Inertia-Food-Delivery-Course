<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreProductRequest;
use App\Http\Requests\Vendor\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService)
    {
    }

    public function create(): Response
    {
        $this->authorize('product.create');

        return Inertia::render('Vendor/Products/Create', [
            'categories'  => Category::all(['id', 'name']),
            'category_id' => request('category_id'),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->createProduct($request->validated());

        return to_route('vendor.menu')
            ->withStatus('Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorize('product.update');

        return Inertia::render('Vendor/Products/Edit', [
            'categories' => Category::get(['id', 'name']),
            'product'    => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->updateProduct($product, $request->validated());

        return to_route('vendor.menu')
            ->withStatus('Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('product.delete');

        $this->productService->deleteProduct($product);

        return to_route('vendor.menu')
            ->withStatus('Product deleted successfully.');
    }
}
