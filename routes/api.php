<?php

use App\Http\Controllers\CatController;
use App\Http\Controllers\DogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::middleware('otp.verified')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/hello', [AuthController::class, 'hello']);
});
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/dogs', [DogController::class, 'create']);
        Route::get('/dogs', [DogController::class, 'index']);
        Route::get('/dogs/my', [DogController::class, 'myDogs']);
        Route::post('/dogs/transfer', [DogController::class, 'transferOwnership']);
        Route::post('/cats', [CatController::class, 'create']);
        Route::get('/cats', [CatController::class, 'index']);
        Route::post('/cats/add', [CatController::class, 'addOwner']);
        Route::delete('/cats/{id}', [CatController::class, 'delete']);
        Route::post('/cats/{id}/transfer', [CatController::class, 'transfer']);
        Route::post('/cats/{id}/favorite', [CatController::class, 'favorite']);
        Route::delete('/cats/{id}/favorite', [CatController::class, 'unfavorite']);
        Route::get('/cats/favorites', [CatController::class, 'myFavorites']);
        Route::delete('/cats/{id}', [CatController::class, 'destroy']);
        Route::get('/cats/trashed', [CatController::class, 'trashed']);
        Route::post('/cats/{id}/restore', [CatController::class, 'restore']);
        Route::delete('/cats/{id}/force', [CatController::class, 'forceDelete']);

    });

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});
use App\Http\Controllers\GiraffeRequestController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/giraffe-requests', [GiraffeRequestController::class, 'index']);
    Route::post('/giraffe-requests', [GiraffeRequestController::class, 'store']);
});
