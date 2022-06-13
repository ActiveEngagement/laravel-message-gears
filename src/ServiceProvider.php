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
            return (new Cloud())
                ->accountId(config('services.messagegears.cloud.account_id'))
                ->apiKey(config('services.messagegears.cloud.api_key'));
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
            return (new Accelerator())
                ->accountId(config('services.messagegears.accelerator.account_id'))
                ->apiKey(config('services.messagegears.accelerator.api_key'))
                ->baseUri(config('services.messagegears.accelerator.base_uri'));
        });

        $this->app->alias(Accelerator::class, 'mg.api.accelerator');
    }
}