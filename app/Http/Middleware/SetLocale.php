<?php
// app/Http/Middleware/SetLocale.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Check if locale exists in session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            // Set default locale from config
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}