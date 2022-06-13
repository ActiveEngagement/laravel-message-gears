<?php

namespace Tests\Unit;

use Actengage\MessageGears\Facades\Accelerator;
use Tests\TestCase;

class AcceleratorTest extends TestCase
{
    public function test_base_uri()
    {
        $this->assertEquals('http://gears.listelixr.net:8080/', Accelerator::instance()->baseUri);
    }

    public function test_uri()
    {
        $this->assertEquals('/test', Accelerator::uri('/test'));
        $this->assertEquals('beta/test', Accelerator::uri('beta/test'));
        $this->assertEquals('/beta/test', Accelerator::uri('/beta/test'));
        $this->assertEquals('beta/betatest', Accelerator::uri('betatest'));
    }

    public function test_post_request()
    {
        Accelerator::mock([
            $this->ok()
        ]);
        
        $response = Accelerator::post('test');
        
        $this->assertEquals(200, $response->getStatusCode());
    }
}