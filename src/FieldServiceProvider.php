<?php

namespace OptimistDigital\MediaField;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use OptimistDigital\MediaField\Classes\MediaHandler;
use OptimistDigital\MediaField\Commands\OptimizeOriginals;
use OptimistDigital\MediaField\Commands\RegenerateThumbnails;
use OptimistDigital\MediaField\Commands\RegenerateWebp;
use OptimistDigital\MediaField\Commands\StripPublicPrefixFromPath;

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
            __DIR__ . '/../config/nova-media-field.php' => config_path('nova-media-field.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/nova-media-field.php',
            'nova-media-field'
        );

        $this->publishes([
            __DIR__ . '/../config/nova-media-field.php' => config_path('nova-media-field.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations' => database_path('migrations'),
        ], 'nova-media-field-migrations');

        if (config('nova-media-field.autoload_migrations', true)) {
            $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        }

        Route::middleware(config('nova-media-field.middlewares', []))
            ->group(__DIR__ . '/routes.php');

        Validator::extend('height', '\OptimistDigital\MediaField\Classes\MediaValidator@height');

        Nova::serving(function (ServingNova $event) {
            Nova::script('media-field', __DIR__ . '/../dist/js/field.js');
            Nova::style('media-field', __DIR__ . '/../dist/css/field.css');
            Nova::script('url-field', __DIR__ . '/../dist/js/urlField.js');
            Nova::script('custom-index-toolbar', __DIR__ . '/../dist/js/toolbar.js');
        });

        Nova::resources([
            config('nova-media-field.media_resource', Media::class)
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                RegenerateThumbnails::class,
                OptimizeOriginals::class,
                RegenerateWebp::class,
                StripPublicPrefixFromPath::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-media');
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MediaHandler::class, function () {
            $mediaHandler = config('nova-media-field.media_handler', MediaHandler::class);
            return new $mediaHandler;
        });
    }
}
