<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Using a closure-based composer...
        // This will be executed when any view is rendered
        View::composer('*', function ($view) {
            // Get settings from cache to avoid hitting the database on every single page load
            $settings = Cache::rememberForever('site_settings', function () {
                // Fetch all settings and create an associative array (key => value)
                return Setting::pluck('value', 'key');
            });

            // Share the $settings variable with all views
            $view->with('settings', $settings);
        });
    }
}
