<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/content/{id}/rate', [RatingController::class, 'store']);
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);

    Route::get('/content/favorites', [FavoriteController::class, 'index']);
    Route::post('/content', [ContentController::class, 'store']);
    Route::get('/content', [ContentController::class, 'indexList']);
    Route::get('/content/{id}', [ContentController::class, 'show']);
    Route::put('/content/{id}', [ContentController::class, 'update']);
    Route::delete('/content/{id}', [ContentController::class, 'destroy']);

    Route::post('/content/{id}/rate', [RatingController::class, 'store']);

    Route::post('/content/{id}/favorite', [FavoriteController::class, 'store']);
});
