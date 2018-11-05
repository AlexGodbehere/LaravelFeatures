<?php

namespace AlexGodbehere\LaravelFeatures;

use Illuminate\Support\ServiceProvider;

class LaravelFeaturesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        // Register the service the package provides.
        $this->app->singleton('laravelfeatures', function ($app) {
            return new LaravelFeatures;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laravelfeatures'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
		// Registering package commands.
		$this->commands([
			AddFeature::class,
			RemoveFeature::class,
			DisableFeature::class,
			EnableFeature::class,
			FreeFeature::class,
			PremiumFeature::class,
			ListFeatures::class,
			ResetFeature::class,
			ResetAllFeatures::class,
			ShowFeatureUsage::class,
			GetFeatureDescription::class
		]);
    }
}
