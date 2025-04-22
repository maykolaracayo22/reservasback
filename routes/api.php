<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomepageSettingController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PaymentController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

// **Autenticación**
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);
Route::middleware([EnsureFrontendRequestsAreStateful::class])->post('/register', [AuthController::class, 'register']);
Route::middleware([EnsureFrontendRequestsAreStateful::class])->post('/login', [AuthController::class, 'login']);
Route::middleware([EnsureFrontendRequestsAreStateful::class])->get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie']);
});
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/user/{id}/roles', [AuthController::class, 'getUserRole'])->middleware('auth:sanctum');

// **Tours**
Route::get('tours', [TourController::class, 'index']);  // Obtener todos los tours
Route::get('tours/{id}', [TourController::class, 'show']);  // Obtener un tour específico
Route::post('tours', [TourController::class, 'store']);  // Crear un nuevo tour
Route::put('tours/{id}', [TourController::class, 'update']);  // Actualizar un tour existente
Route::delete('tours/{id}', [TourController::class, 'destroy']);  // Eliminar un tour

// **Reservas**
Route::post('reservations', [ReservationController::class, 'store']);  // Crear una nueva reserva
Route::get('reservations/{id}', [ReservationController::class, 'show']);  // Obtener una reserva específica
Route::get('reservations', [ReservationController::class, 'index']);  // Obtener todas las reservas
Route::delete('reservations/{id}', [ReservationController::class, 'destroy']);  // Eliminar una reserva
 // Pagos
 Route::get('/payments',       [PaymentController::class, 'index']);   // ← Aquí el GET
 Route::post('/payments',      [PaymentController::class, 'store']);
 Route::get('/payments/{id}',  [PaymentController::class, 'show']);

 Route::middleware(['auth:sanctum', 'role:super-admin'])->group(function () {
    Route::get('/homepage-settings', [HomepageSettingController::class, 'show']);
    Route::post('/homepage-settings', [HomepageSettingController::class, 'update']);
});

Route::get('/home', [HomepageSettingController::class, 'index']);
Route::get('/home', [HomepageSettingController::class, 'public']);
Route::post('/homeedit', [HomepageSettingController::class, 'update']);
Route::put('/homeedit', [HomepageSettingController::class, 'update']);
Route::post('/home', [HomepageSettingController::class, 'update']);
Route::put('/home', [HomepageSettingController::class, 'update']);
Route::post('/home/remove-image', [HomepageSettingController::class, 'removeImage']);
Route::post('/home/remove-image', [HomepageSettingController::class, 'removeImage']);
