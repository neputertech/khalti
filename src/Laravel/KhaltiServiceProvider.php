<?php

namespace Khalti\Laravel;

use Illuminate\Support\ServiceProvider;
use Khalti\Khalti;

final class KhaltiServiceProvider extends ServiceProvider {

    public function boot() {
        $this->publishes([
            __DIR__.'/../config/khalti.php' => config_path('khalti.php')
        ], 'khalti-config');
    }

    public function register() {

        $this->mergeConfigFrom(__DIR__ . '/../config/khalti.php', 'khalti');

        $this->app->bind('khalti', function() {
            return new Khalti();
        });

        $this->app->singleton(Khalti::class, function () {
            return new Khalti();
        });

    }
}