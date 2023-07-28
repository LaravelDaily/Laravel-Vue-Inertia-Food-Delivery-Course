<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreCategoryRequest;
use App\Http\Requests\Vendor\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\Vendor\CategoryCollection;
use App\Http\Resources\Api\V1\Vendor\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request): CategoryCollection
    {
        $this->authorize('category.viewAny');

        $categories = Category::when(
            $request->boolean('products'),
            fn ($q) => $q->with('products')
        )
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->get();

        return new CategoryCollection($categories);
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $restaurant = $request->user()->restaurant;
        $category   = $restaurant->categories()->create($request->validated());

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $this->authorize('category.view');

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Category $category): Response
    {
        $this->authorize('category.delete');

        $category->delete();

        return response()->noContent();
    }
}
