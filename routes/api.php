<?php

use App\Http\Controllers\API\MyProfile;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Examples\CSVController;
use App\Http\Controllers\Examples\FetchDataController;
use App\Http\Controllers\Examples\PDFController;
use App\Http\Controllers\Examples\PermissionsController;
use App\Http\Controllers\Examples\TermsController;
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
  Route::post('auth/login', 'login');
  Route::post('auth/logout', 'logout');
  Route::post('auth/refresh', 'refresh');
});

Route::group(['middleware' => 'auth:api'], function () {
  // Super Admin Only
  Route::resource('users', UserController::class);
  // All users
  Route::get('user/get-profile', [MyProfile::class, 'getProfile']);
  // Ejemplo de asignación de roles
  // Route::get('examples/assign-role', [PermissionsController::class, 'assignRoleUser']);
  // Route::get('examples/get-role', [PermissionsController::class, 'getRoleUser']);
  // Route::get('examples/set-roles-user-objects', [PermissionsController::class, 'setRolesUsersObjects']);
});

// Example Controllers
// Route::get('examples/fetch-and-create-businesses-curl', [FetchDataController::class, 'fetchBusinessData']);
// Route::get('examples/fetch-and-create-businesses-guzzle', [FetchDataController::class, 'fetchBusinessDataGuzzle']);
// Route::post('examples/create-business-draftbit', [FetchDataController::class, 'createBusinessDraftBit']);
// Route::get('examples/show-items-csv', [CSVController::class, 'showItemsCSV']);
// Route::get('examples/create-temporal-download-pdf', [PDFController::class, 'createTemporalPDF']);
// Route::get('examples/create-temporal-stream-pdf', [PDFController::class, 'createAndStreamPDF']);
// Route::get('examples/create-store-download-pdf', [PDFController::class, 'createAndStorePDF']);
// Route::get('examples/term-add-user-location/{id}', [TermsController::class, 'addTermToUser']);
// Route::get('examples/term-get-user-location/{id}', [TermsController::class, 'getTermsUser']);
