<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerCloudApi();
        $this->registerAcceleratorApi();
        $this->registerMessageGearsTransport();

        Mail::extend('messagegears', function (array $config = []) {
            if (Arr::has($config, 'resolver')) {
                /** @var class-string $resolverClass */
                $resolverClass = $config['resolver'];
                $resolver = new $resolverClass;

                if (is_callable($resolver)) {
                    return $resolver($this->app, $config);
                }
            }

            /** @var string $campaignId */
            $campaignId = Arr::get($config, 'campaign_id', '');

            return new MessageGearsTransport(
                $this->app->get(Cloud::class), $campaignId
            );
        });
    }

    /**
     * Register the Cloud API.
     */
    protected function registerCloudApi(): void
    {
        $this->app->singleton(function (): Cloud {
            /** @var array<string, mixed> $cloudConfig */
            $cloudConfig = config('services.messagegears.cloud', []);

            return (new Cloud)->configure(array_filter($cloudConfig));
        });

        $this->app->alias(Cloud::class, 'mg.api.cloud');
    }

    /**
     * Register the Accelerator API.
     */
    protected function registerAcceleratorApi(): void
    {
        $this->app->singleton(function (): Accelerator {
            /** @var array<string, mixed> $acceleratorConfig */
            $acceleratorConfig = config('services.messagegears.accelerator', []);

            return (new Accelerator)->configure(array_filter($acceleratorConfig));
        });

        $this->app->alias(Accelerator::class, 'mg.api.accelerator');
    }

    /**
     * Register the MessageGears transport.
     */
    protected function registerMessageGearsTransport(): void
    {
        $this->app->singleton(function (): MessageGearsTransport {
            /** @var string $campaignId */
            $campaignId = config('services.messagegears.campaign_id', '');

            return new MessageGearsTransport(
                $this->app->get(Cloud::class),
                $campaignId
            );
        });
    }
}
