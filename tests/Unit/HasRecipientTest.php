<?php

namespace Actengage\LaravelMessageGears\Tests\Unit;

use Actengage\LaravelMessageGears\Concerns\HasRecipient;
use Actengage\LaravelMessageGears\Context;
use Actengage\LaravelMessageGears\Recipient;
use Actengage\LaravelMessageGears\Tests\TestCase;
use Actengage\LaravelMessageGears\Tests\User;

class HasRecipientClass {
    use HasRecipient;
}

class HasRecipientTest extends TestCase {

    public function testCanGetSetTheRecipient()
    {
        $instance = new HasRecipientClass;

        $this->assertNull($instance->recipient());

        $instance->to(function($recipient) {
            $this->assertInstanceOf(Recipient::class, $recipient);
        });

        $instance->recipient(new User(['email' => $email = 'test@test.com']));

        $this->assertEquals($email, $instance->recipient()->emailAddress);

        $instance->recipient($email = 'test2@test.com');

        $this->assertEquals($email, $instance->recipient()->emailAddress);

        $instance->recipient(false);

        $this->assertNull($instance->recipient);
    }

}