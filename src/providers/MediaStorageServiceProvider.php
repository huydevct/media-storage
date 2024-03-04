<?php

namespace Huy\MediaStorage\providers;

use Illuminate\Support\ServiceProvider;

class MediaStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/media_storage.php' => config_path('media_storage.php'),
        ], 'media_storage');
        if (!$this->app->routesAreCached()){
            require __DIR__.'/../routes/routes.php';
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/media_storage.php', 'helper'
        );
    }
}