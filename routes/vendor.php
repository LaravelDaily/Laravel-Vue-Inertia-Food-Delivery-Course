<?php

use App\Http\Controllers\Vendor\MenuController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'vendor',
    'as'         => 'vendor.',
    'middleware' => ['auth'],
], function () {
    Route::get('menu', [MenuController::class, 'index'])->name('menu');
});
