<?php

namespace Actengage\LaravelMessageGears\Tests\Unit;

use Actengage\LaravelMessageGears\Concerns\HasAccount;
use Actengage\LaravelMessageGears\Tests\TestCase;

class HasAccountClass {
    use HasAccount;
}

class HasAccountTest extends TestCase {

    public function testCanGetAndSetAccountId()
    {
        $instance = new HasAccountClass;

        $this->assertNull($instance->accountId());
        $this->assertInstanceOf(HasAccountClass::class, $instance->accountId(1));
        $this->assertEquals(1, $instance->accountId());

    }

    public function testCanGetAndSetApiKey()
    {
        $instance = new HasAccountClass;

        $this->assertNull($instance->apiKey());
        $this->assertInstanceOf(HasAccountClass::class, $instance->apiKey(1));
        $this->assertEquals(1, $instance->apiKey());

    }

}