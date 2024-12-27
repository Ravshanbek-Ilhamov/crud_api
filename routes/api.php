<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckUserRole;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[AuthController::class,'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', CheckUserRole::class . ':user,moderator,admin'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
});


Route::middleware(['auth:sanctum', CheckUserRole::class . ':moderator,admin'])->group(function () {
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('/categories/{category}/posts', [CategoryController::class, 'posts']);
    Route::get('/categories/{category}/products', [CategoryController::class, 'products']);
});


Route::middleware(['auth:sanctum', CheckUserRole::class . ':admin'])->group(function () {
    Route::get('/products', [ProductController::class, 'index']); // Already accessible by user/admin group
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

// Route::get('/posts', [PostController::class, 'index']);
// Route::post('/posts', [PostController::class, 'store']);
// Route::get('/posts/{post}', [PostController::class, 'show']);
// Route::put('/posts/{post}', [PostController::class, 'update']);
// Route::delete('/posts/{post}', [PostController::class, 'destroy']);
// Route::get('/posts/{post}/categories', [PostController::class, 'categories']);


