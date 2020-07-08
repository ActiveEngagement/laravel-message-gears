<?php

namespace Actengage\LaravelMessageGears\Tests\Unit;

use Actengage\LaravelMessageGears\Concerns\HasCampaign;
use Actengage\LaravelMessageGears\Tests\TestCase;

class HasCampaignClass {
    use HasCampaign;
}

class HasCampaignTest extends TestCase {

    public function testCanGetAndSetCampaignId()
    {
        $instance = new HasCampaignClass;

        $this->assertNull($instance->campaignId());
        $this->assertInstanceOf(HasCampaignClass::class, $instance->campaignId(1));
        $this->assertEquals(1, $instance->campaignId());

    }

    public function testCanGetAndSetCampaignVersion()
    {
        $instance = new HasCampaignClass;

        $this->assertNull($instance->campaignVersion());
        $this->assertInstanceOf(HasCampaignClass::class, $instance->campaignVersion(1));
        $this->assertEquals(1, $instance->campaignVersion());
    }

}