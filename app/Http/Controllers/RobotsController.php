<?php

namespace App\Http\Controllers;

use App\Support\Robots;

class RobotsController extends Controller
{
    public function __invoke()
    {
        return response(Robots::render(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
