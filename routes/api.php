<?php

use App\Http\Controllers\API\BusinessController;
use App\Http\Controllers\API\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Examples\FetchDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::get('/login', function () {
  return response()->json([
    'success' => false,
    'message' => 'Unauthorized'
  ])->setStatusCode(401);
})->name('login');

Route::controller(AuthController::class)->group(function () {
  Route::post('login', 'login');
  Route::post('logout', 'logout');
  Route::post('refresh', 'refresh');
});

Route::group(['middleware' => 'auth:api'], function () {
  Route::resource('suppliers', SupplierController::class);
  Route::resource('businesses', BusinessController::class);
  Route::resource('items', BusinessController::class);
});

// Example Controllers
Route::get('fetchBusinessData', [FetchDataController::class, 'fetchBusinessData']);
Route::get('fetchBusinessDataGuzzle', [FetchDataController::class, 'fetchBusinessDataGuzzle']);
Route::post('createBusiness', [FetchDataController::class, 'createBusiness']);

