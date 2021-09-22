<?php

namespace Simtabi\Laramodal;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Simtabi\Laramodal\Components\Blade\Trigger;
use Simtabi\Laramodal\Components\Livewire\Laramodal;

class LaramodalServiceProvider extends ServiceProvider
{

    private const PATH = __DIR__ . '/../';

    public static array $assets = [
        'styles'  => [
            'skeleton.css',
            'line-progress.css',
        ],
        'scripts' => [

        ],
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {

        // merge configurations
        $this->mergeConfigFrom(self::PATH .'config/laramodal.php', 'laramodal');

        // load views
        $this->loadViewsFrom(self::PATH . 'resources/views', 'laramodal');

        $this->loadViewComponentsAs('laramodal', [
            'trigger' => Trigger::class,
        ]);

        $this->registerDirectives();
        $this->registerPublishables();

        // livewire component
        Livewire::component('laramodal', Laramodal::class);
    }

    private function registerPublishables(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::PATH . 'config/laramodal.php' => config_path('laramodal.php'),
            ], 'laramodal:config');

            $this->publishes([
                self::PATH . 'public'               => public_path('vendor/laramodal'),
            ], 'laramodal:assets');

            $this->publishes([
                self::PATH . 'resources/views'      => resource_path('views/vendor/laramodal'),
            ], 'laramodal:views');
        }
    }


    private function registerDirectives()
    {

        // inject required css & javascript
        Blade::include('laramodal::init', 'laramodalInit');
       
        Blade::directive('laramodalStyles', function () {
            return $this->getComponentStyles();
        });

        Blade::directive('laramodalScripts', function () {
            return $this->getComponentScripts();
        });
    }

    private function getComponentStyles()
    {
        $styles = self::$assets['styles'] ?? [];

        if (is_array($styles) && (count($styles) >= 1)) {
            return collect(self::$assets['styles'])->map(function($item) {
                return asset("/vendor/laramodal/css/{$item}");
            })->flatten()->map(function($styleUrl) {
                return '<link media="all" type="text/css" rel="stylesheet" href="' . $styleUrl . '">';
            })->implode(PHP_EOL);
        }

        return false;
    }

    private function getComponentScripts()
    {
        $scripts = self::$assets['scripts'] ?? [];

        if (is_array($scripts) && (count($scripts) >= 1)) {
            return collect($scripts)->map(function($item) {
                return asset("/vendor/laramodal/js/{$item}");
            })->flatten()->map(function($scriptUrl) {
                return !empty($scriptUrl) ? '<script src="' . $scriptUrl . '"></script>' : '';
            })->implode(PHP_EOL);

        }

        return false;
    }

}
