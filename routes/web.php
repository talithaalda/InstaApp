<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\AuthMiddleware;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Route;


Route::get('register', [AuthController::class, 'showRegisterForm'])->excludedMiddleware([AuthMiddleware::class]);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login')->excludedMiddleware([AuthMiddleware::class]);
Route::post('register', [AuthController::class, 'register'])->name('register')->excludedMiddleware([AuthMiddleware::class]);
Route::post('login', [AuthController::class, 'login'])->excludedMiddleware([AuthMiddleware::class]);

// Route::middleware([AuthMiddleware::class])->group(function () {
Route::get('/', function () {
    $posts = Post::with('comments')->orderBy('created_at', 'desc')->get();
    return view('home', ['posts' => $posts]);
})->middleware([AuthMiddleware::class]);
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware([AuthMiddleware::class]);
Route::post('posts', [PostController::class, 'store'])->middleware([AuthMiddleware::class]);
Route::post('posts/{postId}/like', [LikeController::class, 'toggleLike'])->middleware([AuthMiddleware::class]);
Route::post('posts/{postId}/comments', [CommentController::class, 'store'])->middleware([AuthMiddleware::class]);
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware([AuthMiddleware::class]);
Route::delete('/comments/{post}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware([AuthMiddleware::class]);
// });
