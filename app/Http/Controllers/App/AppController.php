<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AppController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('app/Index');
    }
}
