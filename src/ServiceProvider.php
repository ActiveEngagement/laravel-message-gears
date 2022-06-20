<?php

namespace Actengage\MessageGears;

use Actengage\MessageGears\Accelerator;
use Actengage\MessageGears\Cloud;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCloudApi();
        $this->registerAcceleratorApi();
    }

    /**
     * Boot any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the Cloud API.
     *
     * @return void
     */
    protected function registerCloudApi(): void
    {
        $this->app->singleton(Cloud::class, function() {
            return (new Cloud())->configure(array_filter(
                config('services.messagegears.cloud') ?? []
            ));
        });

        $this->app->alias(Cloud::class, 'mg.api.cloud');
    }

    /**
     * Register the Accelerator API.
     *
     * @return void
     */
    protected function registerAcceleratorApi(): void
    {
        $this->app->singleton(Accelerator::class, function() {
            return (new Accelerator())->configure(array_filter(
                config('services.messagegears.accelerator') ?? []
            ));
        });

        $this->app->alias(Accelerator::class, 'mg.api.accelerator');
    }
}