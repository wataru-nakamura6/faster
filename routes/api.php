<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MediaUploadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/upload/image', [MediaUploadController::class, 'uploadImage']);
Route::post('/upload/video', [MediaUploadController::class, 'uploadVideo']);
