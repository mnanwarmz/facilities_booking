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
    Route::delete('reservations/{id}', [\App\Http\Controllers\Api\ReservationController::class, 'destroy']);
});
