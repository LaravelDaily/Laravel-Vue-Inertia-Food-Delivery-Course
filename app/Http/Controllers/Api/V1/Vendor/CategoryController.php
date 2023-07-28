<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreCategoryRequest;
use App\Http\Requests\Vendor\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\Vendor\CategoryCollection;
use App\Http\Resources\Api\V1\Vendor\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function __construct(
        public CategoryService $categoryService
    ) {
    }

    public function index(Request $request): CategoryCollection
    {
        $this->authorize('category.viewAny');

        return new CategoryCollection($this->categoryService->getRestaurantCategories(
            withProducts: $request->boolean('products')
        ));
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->createCategory(
            $request->user()->restaurant,
            $request->validated()
        );

        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $this->authorize('category.view');

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $this->categoryService->updateCategory(
            $category,
            $request->validated()
        );

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Category $category): Response
    {
        $this->authorize('category.delete');

        $this->categoryService->deleteCategory($category);

        return response()->noContent();
    }
}
