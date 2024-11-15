<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// This will load the login page and pass the agents to the view when the page is first loaded
Route::get('/', [AgentController::class, 'showLoginPage'])->name('login.page');

Route::get('/dashboard', [DashboardController::class, 'loadSections'])->name('dashboard.page');

// This is the login post on form submit
Route::post('/login', [LoginController::class, 'authenticateLogin'])->name('login');
