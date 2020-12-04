<?php


namespace Sunfire\Form;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class SunfireFormServiceProvider extends ServiceProvider
{

    public function register()
    {
        // register something
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sunfire');
    }

    /**
     * Configure the Jetstream Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
//            $this->registerComponent('base-input-container');
        });
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('sunfire::components.'.$component, 'sunfire-'.$component);
    }

}
