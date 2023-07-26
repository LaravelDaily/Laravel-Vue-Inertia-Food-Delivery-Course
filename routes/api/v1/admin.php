<?php

use App\Http\Controllers\Api\V1\Admin\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'admin',
    'as'         => 'admin.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::apiResource('restaurants', RestaurantController::class);
});
