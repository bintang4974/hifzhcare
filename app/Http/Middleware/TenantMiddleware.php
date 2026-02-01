<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Skip for Super Admin
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Skip for General Users (no tenant)
        if ($user && $user->isGeneralUser()) {
            return $next($request);
        }

        // Check if user has pesantren_id
        if ($user && !$user->pesantren_id) {
            abort(403, 'Anda belum terdaftar di pesantren manapun.');
        }

        // Set tenant context in session
        if ($user && $user->pesantren_id) {
            session(['current_pesantren_id' => $user->pesantren_id]);
        }

        return $next($request);
    }
}
