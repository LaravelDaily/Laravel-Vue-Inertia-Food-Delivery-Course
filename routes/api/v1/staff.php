<?php

use App\Http\Controllers\Api\V1\Staff\OrderController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'staff',
    'as'         => 'staff.',
    'middleware' => ['auth'],
], function () {
    Route::get('orders/past', [OrderController::class, 'past'])->name('orders.past');
    Route::apiResource('orders', OrderController::class);
});
