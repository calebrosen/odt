<?php

use App\Http\Controllers\ReleaseController;
use Illuminate\Support\Facades\Route;

Route::name('releases.')->group(function () {

    // Release page
    Route::get('/releases', [ReleaseController::class, 'init'])->name('page');

    Route::post('/releaseProductSearch', [ReleaseController::class, 'searchForProduct'])->name('search');
});
