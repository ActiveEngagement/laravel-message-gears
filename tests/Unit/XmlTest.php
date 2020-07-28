<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Tests\TestCase;
use Actengage\LaravelMessageGears\Xml;

class XmlTest extends TestCase
{
    /**
     * Test creating XML with arrayables.
     * 
     * @return void
     */
    public function testXmlFromArrayWithArrayable()
    {
        $xml = Xml::fromArray([
            'test' => collect([1, 2, 3])
        ]);

$expected = '<?xml version="1.0"?>
<root><test><0>1</0><1>2</1><2>3</2></test></root>';

        $this->assertEquals($expected, (string) $xml);
    }

}