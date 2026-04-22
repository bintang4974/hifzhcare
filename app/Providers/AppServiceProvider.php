<?php

namespace App\Providers;

use App\Models\Pesantren;
use App\Observers\PesantrenObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Pesantren::observe(PesantrenObserver::class);
    }
}
