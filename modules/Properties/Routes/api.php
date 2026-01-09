<?php

use Illuminate\Support\Facades\Route;
use Modules\Properties\Controllers\PropertyController;

Route::middleware('cors')->prefix('properties')->group(function () {
    // Rutas públicas
    Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/featured', [PropertyController::class, 'featured'])->name('properties.featured');
    Route::get('/search', [PropertyController::class, 'search'])->name('properties.search');
    Route::get('/price-range', [PropertyController::class, 'priceRange'])->name('properties.priceRange');
    Route::get('/{id}', [PropertyController::class, 'show'])->name('properties.show');

    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [PropertyController::class, 'store'])->name('properties.store');
        Route::post('/images/upload', [PropertyController::class, 'uploadImages'])->name('properties.images.upload');
        Route::put('/{id}', [PropertyController::class, 'update'])->name('properties.update');
        Route::delete('/{id}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    });
});

