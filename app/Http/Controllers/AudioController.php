<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class AudioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('audio/Index');
    }
}
