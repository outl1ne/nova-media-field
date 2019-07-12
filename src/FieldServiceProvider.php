<?php

namespace OptimistDigital\MediaField;

use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use OptimistDigital\MediaField\Classes\MediaHandler;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nova-media-field.php' => config_path('nova-media-field.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-media-field.php', 'nova-media-field'
        );

        $this->publishes([
            __DIR__ . '/../config/nova-media-field.php' => config_path('nova-media-field.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        Validator::extend('height', '\OptimistDigital\MediaField\Classes\MediaValidator@height');

        Nova::serving(function (ServingNova $event) {
            Nova::script('media-field', __DIR__.'/../dist/js/field.js');
            Nova::style('media-field', __DIR__.'/../dist/css/field.css');
        });

        Nova::resources([
            Media::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MediaHandler::class, function() {
            return new MediaHandler();
        });
    }
}
