<?php

use App\Http\Controllers\FundController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Fund CRUD routes
    Route::resource('funds', FundController::class)->except(['show', 'edit', 'update']);
    Route::post('funds/{fund}/refresh-price', [FundController::class, 'refreshPrice'])->name('funds.refresh-price');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
