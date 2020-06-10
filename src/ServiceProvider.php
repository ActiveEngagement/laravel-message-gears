<?php

namespace Actengage\LaravelMessageGears;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MessageGears::class, function ($app) {
            return new MessageGears(config('services.messagegears'));
        });

        $this->app->alias(MessageGears::class, 'messagegears');
    }
}