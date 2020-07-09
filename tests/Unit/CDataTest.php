<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\CData;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Illuminate\Support\Collection;

class CDataTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testCData()
    {
        $context = new CData('<div><b>bold</b> unbold</div>');

        $expected = "<![CDATA[<div><b>bold</b> unbold</div>]]>";

        $this->assertEquals($expected, (string) $context);
    }
}