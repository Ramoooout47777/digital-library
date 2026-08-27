<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for admin routes and login/logout
        if ($request->is('admin*') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        $maintenanceMode = Setting::where('key', 'maintenance_mode')->where('group', 'general')->first();

        if ($maintenanceMode && $maintenanceMode->value == '1') {
            $message = Setting::where('key', 'maintenance_message')->where('group', 'general')->value('value')
                       ?? 'Our site is currently under maintenance. We\'ll be back soon!';

            return response()->view('errors.maintenance', ['message' => $message], 503);
        }

        return $next($request);
    }
}
