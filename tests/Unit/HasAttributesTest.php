<?php

namespace Actengage\LaravelMessageGears\Tests\Unit;

use Actengage\LaravelMessageGears\Concerns\HasAttributes;
use Actengage\LaravelMessageGears\Tests\TestCase;

class HasAttributesClass {
    use HasAttributes;

    protected $a;

    protected $b;

    protected $c;

    protected $camelCaseAttribute;

    public function a($a)
    {
        $this->a = $a;

        return $this;
    }
}

class HasAttributesTest extends TestCase {

    public function testCanGetSetAttributes()
    {
        $instance = new HasAttributesClass;

        $this->assertEquals(1, $instance->get('doesNotExist', 1));
        
        $this->assertNull($instance->get('a'));

        $instance->set('a', 1);

        $this->assertEquals(1, $instance->get('a'));

        $instance->set([
            'b' => 2,
            'c' => 3,
            'camel_case_attribute' => 4
        ]);

        $this->assertEquals(2, $instance->get('b'));
        $this->assertEquals(4, $instance->get('camel_case_attribute'));
    }

}