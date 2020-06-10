<?php

namespace Actengage\LaravelMessageGears\Unit;

use Actengage\LaravelMessageGears\Recipient;
use Actengage\LaravelMessageGears\Tests\TestCase;

class RecipientTest extends TestCase
{
    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testRecipientCanBeCreated()
    {
        $recipient = new Recipient([
            'id' => 123,
            'email' => 'test@test.com',
            'test_name' => 123,
            'items' => [
                'a' => 1,
                'b' => 2,
                'C' => 3
            ]
        ]);

        $this->assertEquals(123, $recipient->recipientId);
        $this->assertEquals('test@test.com', $recipient->emailAddress);
        $this->assertEquals(123, $recipient->test_name);

        $recipient->id(456)->email('test2@test.com')->meta('test_name', 456);

        $this->assertEquals(456, $recipient->recipientId);
        $this->assertEquals('test2@test.com', $recipient->emailAddress);
        $this->assertEquals(456, $recipient->test_name);

        return $this->assertEquals([
            'EmailAddress' => 'test2@test.com',
            'RecipientId' => 456,
            'test_name' => 456,
            'items' => [
                'a' => 1,
                'b' => 2,
                'C' => 3
            ]
        ], $recipient->toArray());
    }

    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testRecipientToXml()
    {
        $recipient = new Recipient([
            'email' => 'test@test.com',
            'total' => 500,
            'times' => (object) [
                'start' => '2020-12-31 23:59:59',
                'end' => '2021-12-31 23:59:59',
            ],
            'status' => [
                'coming' => true,
                'on_time' => false
            ],
            'customer' => [
                [
                    'first' => 'John',
                    'total' => 100
                ],
                [
                    'first' => 'Jane',
                    'total' => 75
                ]
            ]
        ]);
        
        $xml = '<Recipient><EmailAddress>test@test.com</EmailAddress><total>500</total><times><start>2020-12-31 23:59:59</start><end>2021-12-31 23:59:59</end></times><status><coming>true</coming><on_time>false</on_time></status><customer><first>John</first><total>100</total></customer><customer><first>Jane</first><total>75</total></customer></Recipient>';

        $this->assertEquals($xml, $recipient->toXml()->toString());
    }

    /**
     * Check that the multiply method returns correct result
     * @return void
     */
    public function testInstantiatingFromString()
    {
        $recipient = new Recipient('test@test.com');

        $this->assertEquals('test@test.com', $recipient->emailAddress);
    }

}