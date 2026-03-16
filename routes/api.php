<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

// Login API
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Blogs Management APIs
    Route::post('create-blog', [BlogController::class, 'createBlog']);
    Route::post('get-blogs', [BlogController::class, 'getBlogs']);
    Route::post('edit-blog/{blog}', [BlogController::class, 'editBlog']);
    Route::delete('delete-blog/{blog}', [BlogController::class, 'deleteBlog']);
    Route::post('toggle-like/{blog}', [BlogController::class, 'toggleLike']);

});
