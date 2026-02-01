<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Base Repository (not needed to bind, it's abstract)
        
        // Hafalan Repository
        $this->app->bind(
            \App\Repositories\Contracts\HafalanRepositoryInterface::class,
            \App\Repositories\Eloquent\HafalanRepository::class
        );
        
        // User Repository
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\UserRepository::class
        );
        
        // Class Repository
        $this->app->bind(
            \App\Repositories\Contracts\ClassRepositoryInterface::class,
            \App\Repositories\Eloquent\ClassRepository::class
        );
        
        // Add more repository bindings here as needed
        // Example:
        // $this->app->bind(
        //     \App\Repositories\Contracts\CertificateRepositoryInterface::class,
        //     \App\Repositories\Eloquent\CertificateRepository::class
        // );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
