<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AudioController;


Route::prefix('app')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        // App route.
        Route::get('/', AppController::class)->name('app.index');
        // Audio routes.
        Route::get('/audio', [AudioController::class, 'index'])->name('audio.index');
    });
