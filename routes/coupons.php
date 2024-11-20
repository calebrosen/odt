<?php

use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

// Coupon management routes
Route::name('coupons.')->group(function () {
    Route::get('/coupons', [CouponController::class, 'init'])->name('page');
    Route::post('/create', [CouponController::class, 'createCoupon'])->name('create');
    Route::post('/delete', [CouponController::class, 'deleteCoupon'])->name('delete');
});
