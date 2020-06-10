<?php

namespace Actengage\LaravelMessageGears\Tests;

use Actengage\LaravelMessageGears\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('services.messagegears', [
            'api_key' => 'API_KEY',
            'account_id' => 'ACCOUNT_ID',
            'campaign_id' => 'CAMPAIGN_ID'
        ]);
    }
}