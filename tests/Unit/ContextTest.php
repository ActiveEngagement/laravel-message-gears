<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Context;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Illuminate\Support\Collection;

class ContextTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testNestedCollectedBeConvertedToXml()
    {
        $context = new Context;
        $context->set('object', (object) ['a' => 1]);
        $context->set('test.nested', new Collection([
            'loop' => new Collection([1, 2, 3]),
            'b' => new Collection(['d' => 1, 'e' => 2, 'f' => 3]),
            'c' =>  3
        ]));

$xml = 
'<?xml version="1.0"?>
<ContextData><object><a>1</a></object><test><nested><loop>1</loop><loop>2</loop><loop>3</loop><b><d>1</d><e>2</e><f>3</f></b><c>3</c></nested></test></ContextData>';
        
        $this->assertEquals($xml, $context->toXml()->toString());
    }
}