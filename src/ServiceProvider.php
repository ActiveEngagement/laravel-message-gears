<?php

namespace Actengage\MessageGears;

use Actengage\MessageGears\Accelerator;
use Actengage\MessageGears\Cloud;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCloudApi();
        $this->registerAcceleratorApi();
        $this->registerMessageGearsTransport();

        Mail::extend('messagegears', function (array $config = []) {
            if(Arr::has($config, 'resolver')) {
                return (new $config['resolver'])($this->app, $config);
            }

            return new MessageGearsTransport(
                $this->app->get(Cloud::class), Arr::get($config, 'campaign_id')
            );
        });
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

    /**
     * Register the MessageGears transport.
     *
     * @return void
     */
    protected function registerMessageGearsTransport(): void
    {
        $this->app->singleton(MessageGearsTransport::class, function() {
            return new MessageGearsTransport(
                $this->app->get(Accelerator::class)
            );
        });
    }
}