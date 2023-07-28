<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function __construct(
        public CategoryService $categoryService
    ) {
    }

    public function index(): Response
    {
        $this->authorize('category.viewAny');

        return Inertia::render('Vendor/Menu', [
            'categories' => $this->categoryService->getRestaurantCategories(
                withProducts: true
            ),
        ]);
    }
}
