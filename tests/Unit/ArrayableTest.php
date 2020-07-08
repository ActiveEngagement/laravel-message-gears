<?php

namespace Actengage\LaravelMessageGears\Tests\Unit;

use Actengage\LaravelMessageGears\Concerns\Arrayable;
use Actengage\LaravelMessageGears\Tests\TestCase;

class ArrayableClass {
    use Arrayable;

    public function __construct(array $params = [])
    {
        foreach($params as $key => $value) {
            $this->$key = $value;
        }
    }
}

class ArrayableTest extends TestCase {

    public function testCastingToArray()
    {
        $instance = new ArrayableClass($params = [
            'a' => 1,
            'b' => 2,
            'c' => collect([1, 2, 3])
        ]);

        $this->assertEquals([
            'a' => 1,
            'b' => 2,
            'c' => [1, 2, 3],
        ], $instance->toArray());
    }

}