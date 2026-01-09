<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Middleware global CORS
Route::middleware('cors')->group(function () {
    // Rutas públicas de autenticación
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});