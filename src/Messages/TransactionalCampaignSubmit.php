<?php

namespace Actengage\LaravelMessageGears\Messages;

use Actengage\LaravelMessageGears\Api\Message;
use Actengage\LaravelMessageGears\Concerns\HasAccount;
use Actengage\LaravelMessageGears\Concerns\HasAttributes;
use Actengage\LaravelMessageGears\Concerns\HasCampaign;
use Actengage\LaravelMessageGears\Concerns\HasContext;
use Actengage\LaravelMessageGears\Concerns\HasRecipient;
use Actengage\LaravelMessageGears\Context;
use Actengage\LaravelMessageGears\Exceptions\MissingCampaignId;
use Carbon\Carbon;

class TransactionalCampaignSubmit extends Message {
    
    use HasAccount, HasAttributes, HasCampaign, HasContext, HasRecipient;

    /**
     * The correlation id.
     *
     * @var mixed
     */
    public $correlationId;

    /**
     * The latest send time.
     *
     * @var \Carbon\Carbon
     */
    public $latestSendTime;
    
    /**
     * The notification email address.
     *
     * @var string
     */
    public $notificationEmailAddress;

    /**
     * Construct the message
     *
     * @return void
     */
    public function __construct(array $params = [])
    {
        $this->context(new Context)
            ->set(config('services.mg', config('services.messagegears')))
            ->set($params);
    }

    /**
     * Get/set the correlation id of the notification.
     *
     * @param  string  $correlationId
     * @return mixed
     */
    public function correlationId(string $correlationId = null)
    {
        if(is_null($correlationId)) {
            return $this->correlationId;
        }

        $this->correlationId = $correlationId;

        return $this;
    }

    /**
     * Set the notication email recipient of the notification.
     *
     * @param  \Carbon\Carbon  $latestSendTime
     * @return mixed
     */
    public function latestSendTime(Carbon $latestSendTime = null)
    {
        if(is_null($latestSendTime)) {
            return $this->latestSendTime;
        }

        $this->latestSendTime = $latestSendTime;

        return $this;
    }

    /**
     * Set the notication email recipient of the notification.
     *
     * @param  string  $notificationEmailAddress
     * @return mixed
     */
    public function notificationEmailAddress(string $notificationEmailAddress)
    {
        if(is_null($notificationEmailAddress)) {
            return $this->notificationEmailAddress;
        }

        $this->notificationEmailAddress = $notificationEmailAddress;

        return $this;
    }

    /**
     * Send the message as an http request
     * 
     * @return  \GuzzleHttp\Psr7\Response
     */
    public function send()
    {
        if(!$this->campaignId) {
            throw new MissingCampaignId;
        }

        $uri = 'campaign/transaction/' . $this->campaignId;

        return app('mg.api.cloud')->post($uri, [
            'json' => array_filter($this->toArray())
        ]);
    }

    /**
     * Cast the message as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'accountId' => $this->accountId,
            'recipient' => $this->recipient->toXml()->toString(),
            'context' => $this->context->toXml()->toString(),
            'campaignVersion' => $this->campaignVersion,
            'configuration' => array_filter([       
                'correlationId' => $this->correlationId,
                'notificationEmailAddress' => $this->notificationEmailAddress,
                'latestSendTime' => $this->latestSendTime,
            ])
        ];
    }

}