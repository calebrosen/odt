<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/coupons.php';
require __DIR__ . '/releases.php';


Route::get('/dashboard', [DashboardController::class, 'loadSections'])->name('dashboard.page');