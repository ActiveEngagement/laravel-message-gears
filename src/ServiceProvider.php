<?php

namespace Actengage\MessageGears;

use Actengage\MessageGears\Accelerator;
use Actengage\MessageGears\Cloud;
use Actengage\MessageGears\Recipient;
use Actengage\MessageGears\Notifications\TransactionalEmail;

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
            return new Cloud(
                config('services.messagegears.account_id'),
                config('services.messagegears.api_key')
            );
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
            return new Accelerator(
                config('services.messagegears.account_id'),
                config('services.messagegears.api_key')
            );
        });

        $this->app->alias(Accelerator::class, 'mg.api.accelerator');
    }
}