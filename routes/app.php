<?php

use App\Http\Controllers\App\AppController;
use App\Http\Controllers\App\SpotifyController;
use App\Http\Controllers\App\TrackController;
use Illuminate\Support\Facades\Route;

Route::prefix('app')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        // App route.
        Route::get('/', AppController::class)->name('app.index');
        // Audio routes.
        Route::get('/tracks', [TrackController::class, 'index'])->name('tracks.index');
        // Spotify only routes.
        Route::post('/spotify/search-tracks', [SpotifyController::class, 'searchTracks'])
            ->name('spotify.search-tracks');
    });
