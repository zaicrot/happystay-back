<?php

use Illuminate\Support\Facades\Route;
use Modules\Testimonials\Controllers\TestimonialController;

Route::middleware('cors')->prefix('testimonials')->group(function () {
    // Rutas públicas
    Route::get('/', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/{id}', [TestimonialController::class, 'show'])->name('testimonials.show');

    // Rutas protegidas
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::put('/{id}', [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::delete('/{id}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    });
});
