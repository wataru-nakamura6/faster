<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScrapeController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;

Route::controller(TopController::class)->group(function () {
    Route::get('/', 'index')->name('top');
});

Route::controller(SiteController::class)
    ->prefix('site')
    ->name('site.')
    ->group(function () {
        Route::post('create', 'create')->name('create');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('site-status', 'siteStatus')->name('site-status');
        Route::post('upload-log', 'uploadLog')->name('upload-log');
});

Route::middleware('auth')->group(function () {
    Route::controller(TopController::class)->group(function () {
        Route::get('/', 'index')->name('top');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::match(['post', 'get'], '/scrape', [ScrapeController::class, 'fetchImages'])->name('scrape');

require __DIR__ . '/auth.php';
