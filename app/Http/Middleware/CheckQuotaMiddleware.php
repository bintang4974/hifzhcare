<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckQuotaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $quotaType = 'santri'): Response
    {
        $user = auth()->user();

        // Skip for Super Admin
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Skip for General Users
        if ($user && $user->isGeneralUser()) {
            return $next($request);
        }

        // Check pesantren
        $pesantren = $user->pesantren;
        if (!$pesantren) {
            return $next($request);
        }

        // Check subscription status
        if (!$pesantren->is_subscription_active) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Langganan pesantren Anda telah berakhir. Silakan perpanjang untuk melanjutkan.');
        }

        // Check quota based on type
        switch ($quotaType) {
            case 'santri':
                if ($pesantren->is_quota_full) {
                    return back()->with('error', 'Kuota santri pesantren sudah penuh. Upgrade paket untuk menambah kuota.');
                }
                break;

            case 'audio':
                // Check audio storage quota (example: 1GB for medium tier)
                $maxStorage = $this->getMaxAudioStorage($pesantren->subscription_tier);
                if ($pesantren->audio_storage_used >= $maxStorage) {
                    return back()->with('error', 'Kuota penyimpanan audio sudah penuh. Upgrade paket untuk menambah kuota.');
                }
                break;
        }

        return $next($request);
    }

    /**
     * Get max audio storage based on tier (in bytes).
     */
    protected function getMaxAudioStorage(string $tier): int
    {
        return match ($tier) {
            'free' => 100 * 1024 * 1024,      // 100 MB
            'low' => 500 * 1024 * 1024,       // 500 MB
            'medium' => 1024 * 1024 * 1024,   // 1 GB
            'large' => 5 * 1024 * 1024 * 1024, // 5 GB
            'enterprise' => 20 * 1024 * 1024 * 1024, // 20 GB
            default => 100 * 1024 * 1024,
        };
    }
}
