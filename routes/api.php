<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TokensController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/tokens/create', TokensController::class);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::resource('appointment', AppointmentController::class)
        ->except(['create', 'edit']);
});
