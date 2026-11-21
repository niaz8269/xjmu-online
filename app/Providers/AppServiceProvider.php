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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Lay the content edited in the admin panel (guides, page FAQs) over the built-in content.
        \App\Support\Cms::applyToConfig();
    }
}
