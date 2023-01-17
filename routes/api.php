<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Authentication
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::get('login', [\App\Http\Controllers\Api\AuthController::class, 'login_page'])->name('login');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Facilities
Route::middleware('auth:sanctum')->group(function () {
    Route::get('facilities', [\App\Http\Controllers\Api\FacilitiesController::class, 'index']);
    Route::post('facilities', [\App\Http\Controllers\Api\FacilitiesController::class, 'store'])->middleware(['middleware' => 'permission:add-facility']);
    Route::post('facilities/{id}', [\App\Http\Controllers\Api\FacilitiesController::class, 'update'])->middleware(['middleware' => 'permission:edit-facility']);
    Route::delete('facilities/{id}', [\App\Http\Controllers\Api\FacilitiesController::class, 'destroy'])->middleware(['middleware' => 'permission:delete-facility']);
});

// Reservations
Route::middleware('auth:sanctum')->group(function () {
    Route::get('reservations', [\App\Http\Controllers\Api\ReservationController::class, 'index'])->middleware(['middleware' => 'permission:view-reservations']);
    Route::get('reservations/{id}', [\App\Http\Controllers\Api\ReservationController::class, 'show']);
    Route::get('reservations/user/{username}', [\App\Http\Controllers\Api\ReservationController::class, 'showUserReservations']);
    Route::post('reservations', [\App\Http\Controllers\Api\ReservationController::class, 'store']);
    Route::post('reservations/{id}', [\App\Http\Controllers\Api\ReservationController::class, 'update']);
    Route::post('reservations/cancel/{id}', [\App\Http\Controllers\Api\ReservationController::class, 'cancel'])
        ->name('cancel-reservation');
    Route::delete('reservations/{id}', [\App\Http\Controllers\Api\ReservationController::class, 'destroy']);
});

// Payments
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('payments', [\App\Http\Controllers\Api\PaymentController::class, 'index']);
    Route::post('payments', [\App\Http\Controllers\Api\PaymentController::class, 'store']);
    Route::post('payments/{id}', [\App\Http\Controllers\Api\PaymentController::class, 'update']);
    // Route::delete('payments/{id}', [\App\Http\Controllers\Api\PaymentController::class, 'destroy']);
});

// Admin
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('users', [\App\Http\Controllers\Api\UserController::class, 'index'])->middleware(['middleware' => 'permission:view-users']);
    Route::post('users', [\App\Http\Controllers\Api\UserController::class, 'store'])->middleware(['middleware' => 'permission:add-users']);
    Route::post('users/{id}', [\App\Http\Controllers\Api\UserController::class, 'update'])
        ->middleware(['middleware' => 'permission:edit-users']);
    Route::delete('users/{id}', [\App\Http\Controllers\Api\UserController::class, 'destroy'])
        ->middleware(['middleware' => 'permission:delete-users']);
});
