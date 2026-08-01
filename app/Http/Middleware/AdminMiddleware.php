<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has admin role
        $user = Auth::user();
        
        // Check if user has role 'admin' or 'super-admin'
        if (!$user->hasRole(['admin', 'super-admin'])) {
            abort(403, 'Unauthorized access. Admin only.');
        }

        return $next($request);
    }
}