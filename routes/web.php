<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScrapeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::controller(App\Http\Controllers\TopController::class)->group(function () {
    Route::match(['post', 'get'], '/', 'index')->name('top');
    Route::match(['post', 'get'], '/store_site', 'storeSite')->name('storeSite');
});

Route::get('/dashboard', function () {
    return Inertia::render('Laravel/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/scrape', [ScrapeController::class, 'fetchImages'])->name('scrape');

require __DIR__.'/auth.php';
