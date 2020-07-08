<?php

namespace Actengage\LaravelMessageGears;

use Actengage\LaravelMessageGears\Api\Accelerator;
use Actengage\LaravelMessageGears\Api\Cloud;

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
     * Register the Cloud API.
     *
     * @return void
     */
    public function registerCloudApi()
    {
        $this->app->singleton(Cloud::class, function ($app) {
            return new Cloud(config('services.mg', config('services.messagegears')));
        });

        $this->app->alias(Cloud::class, 'mg.api.cloud');
    }

    /**
     * Register the Accelerator API.
     *
     * @return void
     */
    public function registerAcceleratorApi()
    {
        $this->app->singleton(Accelerator::class, function ($app) {
            return new Accelerator(config('services.mg', config('services.messagegears')));
        });

        $this->app->alias(Accelerator::class, 'mg.api.accelerator');
    }
}