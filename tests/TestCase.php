<?php

namespace Tests;

use Actengage\MessageGears\ServiceProvider;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();

        $this->artisan('migrate', [
            '--database' => 'testbench',
        ]);

        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('services.messagegears', [
            'account_id' => env('MESSAGEGEARS_ACCOUNT_ID', 'ACCOUNT_ID'),
            'api_key' => env('MESSAGEGEARS_API_KEY', 'API_KEY'),
            'campaign_id' => env('MESSAGEGEARS_CAMPAIGN_ID', 'CAMPAIGN_ID'),
        ]);
    }

    protected function authenticate(): Response
    {
        return $this->ok([
            'token' => md5(microtime()),
            'expirationDate' => now()->addHour(),
        ]);
    }

    protected function ok(array $body = []): Response
    {
        return $this->response(200, $body);
    }

    protected function response(int $status = 200, array $body = []): Response
    {
        return new Response($status, [], json_encode($body));
    }
}
