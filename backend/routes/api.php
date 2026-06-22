<?php

use App\Http\Controllers\Api\AdminBannerController;
use App\Http\Controllers\Api\AdminPostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);
Route::get('banners', [BannerController::class, 'index']);

// Admin routes
Route::post('admin/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('admin/logout', [AuthController::class, 'logout']);

    Route::get('admin/posts', [AdminPostController::class, 'index']);
    Route::post('admin/posts', [AdminPostController::class, 'store']);
    Route::get('admin/posts/{id}', [AdminPostController::class, 'show']);
    Route::put('admin/posts/{id}', [AdminPostController::class, 'update']);
    Route::delete('admin/posts/{id}', [AdminPostController::class, 'destroy']);
    Route::post('admin/posts/{id}/toggle-publish', [AdminPostController::class, 'togglePublish']);
    Route::post('admin/upload-image', [AdminPostController::class, 'uploadImage']);

    Route::get('admin/banners', [AdminBannerController::class, 'index']);
    Route::post('admin/banners', [AdminBannerController::class, 'store']);
    Route::put('admin/banners/{id}', [AdminBannerController::class, 'update']);
    Route::delete('admin/banners/{id}', [AdminBannerController::class, 'destroy']);
});
