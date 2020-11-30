<?php


namespace Sunfire\Form;


use Illuminate\Support\ServiceProvider;

class SunfireFormServiceProvider extends ServiceProvider
{

    public function register()
    {
        // register something
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sunfire');
        // bootsrap app services
    }
}
