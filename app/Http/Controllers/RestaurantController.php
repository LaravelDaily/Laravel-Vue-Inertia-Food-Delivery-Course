<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Inertia\Inertia;
use Inertia\Response;

class RestaurantController extends Controller
{
    public function show(Restaurant $restaurant): Response
    {
        return Inertia::render('Restaurant', [
            'restaurant' => $restaurant->load('categories.products'),
        ]);
    }
}
