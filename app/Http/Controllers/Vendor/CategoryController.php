<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreCategoryRequest;
use App\Http\Requests\Vendor\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function __construct(
        public CategoryService $categoryService
    ) {
    }

    public function create(): Response
    {
        $this->authorize('category.create');

        return Inertia::render('Vendor/Categories/Create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory(
            $request->user()->restaurant,
            $request->validated()
        );

        return to_route('vendor.menu')
            ->withStatus('Product Category created successfully.');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('Vendor/Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryService->updateCategory(
            $category,
            $request->validated()
        );

        return to_route('vendor.menu')
            ->withStatus('Product Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->categoryService->deleteCategory($category);

        return to_route('vendor.menu')
            ->withStatus('Product Category deleted successfully.');
    }
}
