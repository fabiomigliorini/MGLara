<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class LanguageProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_TIME, config('app.locale') . '.utf8');
        Carbon::setLocale($this->app->getLocale());
    }
}
