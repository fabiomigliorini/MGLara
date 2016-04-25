<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;

class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['breadcrumbs'] = $this->app->share(function($app)
        {
            $breadcrumbs = $this->app->make('MGLara\Breadcrumbs\Manager');

            //$viewPath = __DIR__ . '';

            //$this->loadViewsFrom($viewPath, 'breadcrumbs');
            //$this->loadViewsFrom($viewPath, 'laravel-breadcrumbs'); // Backwards-compatibility with 2.x

            $breadcrumbs->setView('../views/includes/breadcrumbs.blade.php');

            return $breadcrumbs;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /*
        $configFile = __DIR__ . '/../config/breadcrumbs.php';

        $this->mergeConfigFrom($configFile, 'breadcrumbs');

        $this->publishes([
            $configFile => config_path('breadcrumbs.php')
        ]);

        $this->registerBreadcrumbs();
         * 
         */
        
        parent::boot();
    }
}
