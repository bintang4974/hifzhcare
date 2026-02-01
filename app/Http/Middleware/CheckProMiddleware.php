<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Check if user is PRO
        if (!$user || !$user->isProUser()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fitur ini hanya tersedia untuk pengguna PRO.',
                    'upgrade_url' => route('upgrade.pro')
                ], 403);
            }

            return redirect()
                ->route('dashboard')
                ->with('error', 'Fitur ini hanya tersedia untuk pengguna PRO. Upgrade sekarang untuk mengakses fitur lengkap!');
        }

        // Check if PRO subscription is still active
        if ($user->pro_expired_at && $user->pro_expired_at->isPast()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Langganan PRO Anda telah berakhir.',
                    'renew_url' => route('upgrade.pro')
                ], 403);
            }

            return redirect()
                ->route('dashboard')
                ->with('error', 'Langganan PRO Anda telah berakhir. Perpanjang sekarang untuk melanjutkan akses.');
        }

        return $next($request);
    }
}
