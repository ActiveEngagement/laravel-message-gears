<?php

namespace Actengage\LaravelMessageGears\Tests\Unit;

use Actengage\LaravelMessageGears\Concerns\HasContext;
use Actengage\LaravelMessageGears\Context;
use Actengage\LaravelMessageGears\Tests\TestCase;

class HasContextClass {
    use HasContext;
}

class HasContextTest extends TestCase {

    public function testCanGetSetTheContext()
    {
        $instance = new HasContextClass;

        $this->assertNull($instance->context);
        $this->assertInstanceOf(Context::class, $instance->context());
        
        $this->assertNull($instance->context->get('a'));

        $instance->context('a', 1);

        $this->assertEquals(1, $instance->context->get('a'));
        
        $instance->context([
            'b' => 2,
            'c' => 3
        ]);
        
        $this->assertEquals([
            'a' => 1,
            'b' => 2,
            'c' => 3
        ], $instance->context()->toArray());

        $instance->context(new Context([
            'a' => 1
        ]));

        $this->assertEquals([
            'a' => 1
        ], $instance->context()->toArray());
    }

}