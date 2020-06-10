<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Arr;
use Actengage\LaravelMessageGears\Context;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Illuminate\Support\Collection;

class ArrTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testStudlyCase()
    {
        $subject = Arr::studlyKeys([
            'test_a' => 1,
            'testB' => 2,
            'Test_c' => 3
        ]);

        $this->assertEquals([
            'TestA',
            'TestB',
            'TestC'
        ], array_keys($subject));
    }
}