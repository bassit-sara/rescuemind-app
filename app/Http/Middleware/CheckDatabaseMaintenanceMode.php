<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDatabaseMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't block admin/super-admin paths or login/auth paths
        if ($request->is('admin/*') || $request->is('super-admin/*') || $request->is('login') || $request->is('logout') || $request->is('maintenance')) {
            return $next($request);
        }

        // Allow super admins and admins to bypass maintenance
        if (auth()->check() && (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))) {
            return $next($request);
        }

        if (setting('maintenance_mode', false)) {
            return response()->view('maintenance', [
                'message' => setting('maintenance_message', 'กำลังปรับปรุงระบบ กรุณากลับมาใหม่ในภายหลัง')
            ], 503);
        }

        return $next($request);
    }
}
