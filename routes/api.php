<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

// Login API
Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'logout']);

    // Blogs
    Route::post('create-blog', [BlogController::class, 'createBlog']);
    Route::post('get-blogs', [BlogController::class, 'getBlogs']);
    Route::post('edit-blog/{blog}', [BlogController::class, 'editBlog']);
    Route::delete('delete-blog/{blog}', [BlogController::class, 'deleteBlog']);

});
