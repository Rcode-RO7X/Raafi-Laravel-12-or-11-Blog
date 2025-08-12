<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

// =====================
// Public Routes
// =====================
Route::get('/', [HomeController::class, 'index']);

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');

use App\Http\Controllers\CommentController;

// =====================
// Comment Routes
// =====================
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{id}/like', [CommentController::class, 'like'])->name('comments.like');
Route::post('/comments/{id}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

// =====================
// Filament handles Auth
// =====================
// Login, Register, Forgot Password, Logout
// sudah otomatis di-handle oleh Filament