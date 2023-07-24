<?php

use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\MenuController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\StaffMemberController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'vendor',
    'as'         => 'vendor.',
    'middleware' => ['auth'],
], function () {
    Route::get('menu', [MenuController::class, 'index'])->name('menu');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('staff-members', StaffMemberController::class);
});
