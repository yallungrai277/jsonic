<?php

namespace App\Http\Controllers\App;

use App\Connectors\SpotifyConnector;
use App\Http\Controllers\Controller;
use App\Http\Requests\Spotify\SpotifyTrackFormRequest;

class SpotifyController extends Controller
{
    public function searchTracks(SpotifyTrackFormRequest $request)
    {
        dd(app(SpotifyConnector::class));
        dd($request->all());
        // Logic to search tracks using Spotify API and return results.

        // But first install socialite/spotify package and set up the SpotifyConnector to use it.
    }
}
