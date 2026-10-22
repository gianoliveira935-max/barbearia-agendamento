<?php

use App\Http\Controllers\ServiceController;

Route::get('/servicos', [ServiceController::class, 'index'])->name('services.index');
Route::post('/servicos', [ServiceController::class, 'store'])->name('services.store');
Route::delete('/servicos/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');


use App\Http\Controllers\AppointmentController;

Route::get('/agendar', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/agendar', [AppointmentController::class, 'store'])->name('appointments.store');