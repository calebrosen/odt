<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->group(function () {

    // Show login page
    Route::get('/', [AgentController::class, 'showLoginPage'])->name('login.page');

    // Login post request (form submit)
    Route::post('/login', [LoginController::class, 'authenticateLogin'])->name('login');

    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
});
