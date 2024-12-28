<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckUserRole;
use App\Models\Product;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth-user', [AuthController::class, 'authUser'])->middleware('auth:sanctum');


Route::post('/take-token', [AuthController::class, 'takeToken']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);


Route::middleware(['auth:sanctum', CheckUserRole::class . ':user,admin'])->group(function () {
    Route::post('/tasks', [TaskController::class, 'store']);
});


Route::middleware(['auth:sanctum', CheckUserRole::class . ':moderator,admin'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
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

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware('auth:sanctum');
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);


    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::get('/comments/{comment}', [CommentController::class, 'show']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});



