<?php

namespace App\Providers;

use App\Support\SiteChrome;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/** Feeds the shared public partials (resources/views/partials/*) with their data. See docs/CONVERSION.md "Public partials contract". */
class PublicPartialsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SiteChrome::class);
    }

    public function boot(): void
    {
        View::composer('partials.header', function ($view) {
            $c = app(SiteChrome::class);
            $view->with('siteHead', $c->head($view->getData()))->with('siteTop', $c->top());
        });
        View::composer('partials.footer', fn ($view) => $view->with('siteFoot', app(SiteChrome::class)->footer()));
        View::composer('partials.consultants', fn ($view) => $view->with('siteConsultants', app(SiteChrome::class)->consultants()));
        View::composer('partials.event-register-modal', fn ($view) => $view->with('siteModal', app(SiteChrome::class)->registerModal()));
    }
}
