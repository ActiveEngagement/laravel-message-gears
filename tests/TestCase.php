<?php

declare(strict_types=1);

namespace Tests;

use Actengage\MessageGears\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Override;

class TestCase extends \Orchestra\Testbench\TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->artisan('migrate', [
            '--database' => 'testbench',
        ]);
    }

    /**
     * @return array<int, class-string>
     */
    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    #[Override]
    protected function getEnvironmentSetUp($app): void
    {
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        $app->make(Repository::class)->set('database.default', 'testbench');

        $app->make(Repository::class)->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app->make(Repository::class)->set('services.messagegears', [
            'account_id' => env('MESSAGEGEARS_ACCOUNT_ID', 'ACCOUNT_ID'),
            'api_key' => env('MESSAGEGEARS_API_KEY', 'API_KEY'),
            'campaign_id' => env('MESSAGEGEARS_CAMPAIGN_ID', 'CAMPAIGN_ID'),
        ]);
    }
}
