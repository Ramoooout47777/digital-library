<?php
// routes/api.php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Public Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Public Book Routes
    Route::get('/home', function () {
        return response()->json([
            'message' => 'Book Selling API v1',
            'version' => '1.0.0',
        ]);
    });

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/featured', [BookController::class, 'featured']);
    Route::get('/books/new-releases', [BookController::class, 'newReleases']);
    Route::get('/books/popular', [BookController::class, 'popular']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::get('/books/{book}/preview', [BookController::class, 'preview']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/tree', [CategoryController::class, 'tree']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Protected Routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Auth Routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/profile/change-password', [AuthController::class, 'changePassword']);
        Route::post('/profile/refresh-token', [AuthController::class, 'refreshToken']);

        // Book Routes (Protected)
        Route::post('/books/{book}/favorite', [BookController::class, 'toggleFavorite']);
        Route::get('/books/{book}/download', [BookController::class, 'download']);
        Route::post('/books/{book}/reviews', [BookController::class, 'addReview']);

        // Library Routes
        Route::get('/library', [LibraryController::class, 'index']);
        Route::get('/library/history', [LibraryController::class, 'history']);
        Route::get('/library/check/{bookId}', [LibraryController::class, 'check']);

        // Favorite Routes
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites', [FavoriteController::class, 'store']);
        Route::delete('/favorites/{book}', [FavoriteController::class, 'destroy']);
        Route::post('/favorites/{book}/toggle', [FavoriteController::class, 'toggle']);

        // Order Routes
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/complete', [OrderController::class, 'complete']);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    });
});