<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Api\Accelerator;
use Actengage\LaravelMessageGears\Api\Cloud;
use Actengage\LaravelMessageGears\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testCloudApiInstance()
    {
        $this->assertInstanceOf(Cloud::class, app('mg.api.cloud'));
    }

    public function testAcceleratorApiInstance()
    {
        $this->assertInstanceOf(Accelerator::class, app('mg.api.accelerator'));
    }
}