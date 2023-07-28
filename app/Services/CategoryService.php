<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Support\Collection;

class CategoryService
{
    public function getRestaurantCategories(bool $withProducts = false): Collection
    {
        return Category::when(
            $withProducts,
            fn ($q) => $q->with('products')
        )
            ->where('restaurant_id', auth()->user()->restaurant->id)
            ->get();
    }

    public function createCategory(Restaurant $restaurant, array $attributes): Category
    {
        return $restaurant->categories()->create($attributes);
    }

    public function updateCategory(Category $category, array $attributes): Category
    {
        $category->update($attributes);

        return $category;
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }
}
