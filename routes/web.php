<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;

Route::get('/servicos', [ServiceController::class, 'index'])->name('services.index');
Route::post('/servicos', [ServiceController::class, 'store'])->name('services.store');
Route::delete('/servicos/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

Route::get('/agendar', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/agendar', [AppointmentController::class, 'store'])->name('appointments.store');

Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');