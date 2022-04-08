<?php

namespace Simtabi\Laramodal;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;
use Simtabi\Laramodal\Livewire\Laramodal;

class LaramodalServiceProvider extends ServiceProvider
{

    private string $packageName = 'laramodal';
    private const  PACKAGE_PATH = __DIR__ . '/../';

    public static array $assets  = [
        'css' => [
            'skeleton.css',
            'line-progress.css',
        ],
        'js'  => [

        ],
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadTranslationsFrom(self::PACKAGE_PATH . "resources/lang/", $this->packageName);
        $this->loadMigrationsFrom(self::PACKAGE_PATH.'/../database/migrations');
        $this->loadViewsFrom(self::PACKAGE_PATH . "resources/views", $this->packageName);
        $this->mergeConfigFrom(self::PACKAGE_PATH . "config/config.php", $this->packageName);
    }

    public function boot()
    {
        $this->registerDirectives();
        $this->registerConsoles();

        $this->configureComponents();

        // livewire component
        Livewire::component('laramodal', Laramodal::class);
    }

    private function registerConsoles(): static
    {
        if ($this->app->runningInConsole())
        {

            $this->publishes([
                self::PACKAGE_PATH . "config/config.php"               => config_path("{$this->packageName}.php"),
            ], "{$this->packageName}:config");

            $this->publishes([
                self::PACKAGE_PATH . "public"                          => public_path("vendor/{$this->packageName}"),
            ], "{$this->packageName}:assets");

            $this->publishes([
                self::PACKAGE_PATH . "resources/views"                 => resource_path("views/vendor/{$this->packageName}"),
            ], "{$this->packageName}:views");

            $this->publishes([
                self::PACKAGE_PATH . "resources/lang"                  => $this->app->langPath("vendor/{$this->packageName}"),
            ], "{$this->packageName}:translations");
        }

        return $this;
    }

    private function registerDirectives()
    {

        // inject required css & javascript
        Blade::include('laramodal::init', 'laramodalInit');

        Blade::directive('laramodalCss', function () {
            return $this->getComponentStyles();
        });

        Blade::directive('laramodalJs', function () {
            return $this->getComponentScripts();
        });
    }

    private function getComponentStyles()
    {
        $styles = self::$assets['css'] ?? [];

        if (is_array($styles) && (count($styles) >= 1)) {
            return collect($styles)->map(function($item) {
                return asset("/vendor/laramodal/css/{$item}");
            })->flatten()->map(function($styleUrl) {
                return '<link media="all" type="text/css" rel="stylesheet" href="' . $styleUrl . '">';
            })->implode(PHP_EOL);
        }

        return false;
    }

    private function getComponentScripts()
    {
        $scripts = self::$assets['js'] ?? [];

        if (is_array($scripts) && (count($scripts) >= 1)) {
            return collect($scripts)->map(function($item) {
                return asset("/vendor/laramodal/js/{$item}");
            })->flatten()->map(function($scriptUrl) {
                return !empty($scriptUrl) ? '<script src="' . $scriptUrl . '"></script>' : '';
            })->implode(PHP_EOL);

        }

        return false;
    }

    /**
     * Configure the Jetstream Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('alerts', 'show');
            $this->registerComponent('trigger', 'modal');
        });
    }

    /**
     * Register the given component.
     *
     * @param string $component
     * @param string|null $alias
     * @param string|null $folder
     * @return void
     */
    protected function registerComponent(string $component, ?string $alias = null, ?string $folder = null)
    {
        Blade::component($this->packageName . '::components.' . (!empty($folder) ? "$folder." : '') . $component, (!empty($alias) ? "$alias-" : '') . $component);
    }

}
