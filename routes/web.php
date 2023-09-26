<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Examples\FetchPostsController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});

// Blog
Route::get('/blog/', [PostController::class, 'index']);
Route::get('/blog/{post_id}/{slug}', [PostController::class, 'show']);

Route::resource('/comments', CommentController::class);

Route::get('/fetch-posts', [FetchPostsController::class, 'fetchPostsData']);
