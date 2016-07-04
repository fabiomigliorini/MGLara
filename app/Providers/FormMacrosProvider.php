<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use File;

class FormMacrosProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach(File::glob(app_path() .'/FormMacros/*.php') as $macro) {
            require $macro;
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
