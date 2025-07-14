<?php

namespace Prasanna\WeightShipping\Providers;

use Illuminate\Support\ServiceProvider;

class WeightShippingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Config/carriers.php' => config_path('carriers.php'),
        ], 'weight-shipping-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Prasanna\WeightShipping\Console\InstallCommand::class,
            ]);
        }
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/carriers.php', 'carriers'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/system.php', 'core'
        );
    }
}