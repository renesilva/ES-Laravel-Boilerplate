<?php

use App\Http\Controllers\AuthController;
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

Route::get('auth/password/reset/{token}', function (string $token = '') {
  return view('auth.password_reset', ['token' => $token]);
})->name('password.reset');

Route::post('auth/password/reset', [AuthController:: class, 'passwordReset']);

Route::get('/auth/password/reset', function (string $token = '') {
  return view('auth.password_reset', ['token' => $token]);
});
