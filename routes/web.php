<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReleaseController;
use Illuminate\Support\Facades\Route;

// This will load the login page and pass the agents to the view when the page is first loaded
Route::get('/', [AgentController::class, 'showLoginPage'])->name('login.page');

Route::get('/dashboard', [DashboardController::class, 'loadSections'])->name('dashboard.page');

// Deleting session cookies to log user out
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout.button');

// On load of coupon page
Route::get('/coupons', [CouponController::class, 'init'])->name('coupons.page');

// Creating coupon
Route::post('/createCoupon', [CouponController::class, 'createCoupon'])->name('createCoupon.action');

// Deleting session cookies to log user out
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout.button');

// This is the login post on form submit
Route::post('/login', [LoginController::class, 'authenticateLogin'])->name('login');
