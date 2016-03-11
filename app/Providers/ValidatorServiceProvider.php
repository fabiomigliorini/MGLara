<?php

namespace MGLara\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('validFilial', function ($attribute, $value, $parameters)
        {
            if (in_array($value, Auth::user()->filiais())){
                return true;
            } else {
                return false;
            }  
        });          
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::boot();

    }
}
