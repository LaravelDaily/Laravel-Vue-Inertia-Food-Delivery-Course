<?php

use App\Http\Controllers\Api\V1\Vendor\CategoryController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'vendor',
    'as'         => 'vendor.',
    'middleware' => ['auth:sanctum'],
], function () {
    Route::apiResource('categories', CategoryController::class);
});
