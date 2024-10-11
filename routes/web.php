<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $posts = Post::with('comments')->orderBy('created_at', 'desc')->get();

    return view('home', ['posts' => $posts]);
});
Route::get('register', [AuthController::class, 'showRegisterForm']);
Route::get('login', [AuthController::class, 'showLoginForm']);
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('posts', [PostController::class, 'store']);
    Route::post('posts/{postId}/like', [LikeController::class, 'toggleLike']);
    Route::post('posts/{postId}/comments', [CommentController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/comments/{post}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
