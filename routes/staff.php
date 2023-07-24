<?php

use App\Http\Controllers\Staff\OrderController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'staff',
    'as'         => 'staff.',
    'middleware' => ['auth'],
], function () {
    Route::resource('orders', OrderController::class);
});
