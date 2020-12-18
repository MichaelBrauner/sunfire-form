<?php


namespace Sunfire\Form;


use Sunfire\Form\View\Components\BaseInputContainer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Sunfire\Form\Services\Options;
use Illuminate\Support\Facades\App;

class SunfireFormServiceProvider extends ServiceProvider
{

    public function register()
    {
        App::bind('options', function () {
            return new Options();
        });

        $this->app->alias('Options', Options::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/autocomplete-options.php', 'sunfire.autocomplete.options');
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sunfire');

//        $this->configureComponents();
    }

    /**
     * Configure the Jetstream Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            Blade::component('sunfire-base-input-container', 'sunfire-base-input-container');
        });
    }

    /**
     * Register the given component.
     *
     * @param string $component
     * @return void
     */
    protected function registerComponent(string $component, $class = null)
    {
        Blade::component('sunfire::components.' . $component, 'sunfire-' . $component, $class);
    }

}
