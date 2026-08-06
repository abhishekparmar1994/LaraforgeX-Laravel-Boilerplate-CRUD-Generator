<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Auto-discover and register all domain service providers
        $domainsPath = app_path('Domains');
        if (is_dir($domainsPath)) {
            $directories = glob($domainsPath . '/*', GLOB_ONLYDIR);
            foreach ($directories as $directory) {
                $domainName = basename($directory);
                $providerClass = "App\\Domains\\{$domainName}\\{$domainName}ServiceProvider";
                if (class_exists($providerClass)) {
                    $this->app->register($providerClass);
                }
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register domain policies explicitly
        \Illuminate\Support\Facades\Gate::policy(
            \App\Domains\User\Models\User::class,
            \App\Domains\User\Policies\UserPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Domains\Settings\Models\Settings::class,
            \App\Domains\Settings\Policies\SettingsPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Domains\Media\Models\Media::class,
            \App\Domains\Media\Policies\MediaPolicy::class
        );
        \Illuminate\Support\Facades\Gate::policy(
            \App\Domains\AuditLog\Models\AuditLog::class,
            \App\Domains\AuditLog\Policies\AuditLogPolicy::class
        );

        // Force HTTPS scheme in production or behind SSL proxy
        if (config('app.env') === 'production' || request()->header('X-Forwarded-Proto') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Define system gates
        \Illuminate\Support\Facades\Gate::define('admin-only', function ($user) {
            return $user->hasRole('administrator');
        });
    }
}
