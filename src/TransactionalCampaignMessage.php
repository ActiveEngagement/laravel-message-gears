<?php

namespace Actengage\LaravelMessageGears;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\User;

class TransactionalCampaignMessage implements Arrayable, Requestable, Xmlable {
    
    use XmlRequest;

    /**
     * The request action.
     *
     * @var string
     */
    const ACTION = 'TransactionalCampaignSubmit';
    
    /**
     * The account id.
     *
     * @var string
     */
    public $accountId;
    
    /**
     * The api key.
     *
     * @var string
     */
    public $apiKey;
    
    /**
     * The attachments
     *
     * @var array
     */
    public $attachments = [];
    
    /**
     * The campaign id.
     *
     * @var string
     */
    public $campaignId;
    
    /**
     * The email context.
     *
     * @var Actengage\LaravelMessageGears\Context
     */
    public $context;
    
    /**
     * The correlation id.
     *
     * @var mixed
     */
    public $correlationId;
    
    /**
     * The email recipient.
     *
     * @var Actenage\LaravelMessageGears\Recipient
     */
    public $recipient;
    
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
        $this->context = new Context;

        foreach($params as $key => $value) {
            if(method_exists($this, $key)) {
                $this->$key($value);
            }
            else if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Set the account id of the notification.
     *
     * @param  string  $accountId
     * @return $this
     */
    public function accountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Set the api key of the notification.
     *
     * @param  string  $apiKey
     * @return $this
     */
    public function apiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Add an attachment to the notification.
     *
     * @param  string  $url
     * @param  string  $content
     * @param  string  $filename
     * @param  string  $contentType
     * @return $this
     */
    public function attachment(string $url, string $content, string $filename = null, string $contentType = null)
    {
        $this->attachments[] = [
            'AttachmentUrl' => $url,
            'AttachmentContent' => $content,
            'AttachmentName' => $filename,
            'AttachmentContentType' => $contentType
        ];

        return $this;
    }

    /**
     * Add multiple attachments to the notification.
     *
     * @param  array  $attachments
     * @return $this
     */
    public function attachments(array $attachments)
    {
        foreach($attachments as $attachment) {
            $this->attachment(...$attachment);
        }

        return $this;
    }

    /**
     * Set the campaign id of the notification.
     *
     * @param  string  $campaignId
     * @return $this
     */
    public function campaignId($campaignId)
    {
        $this->campaignId = $campaignId;

        return $this;
    }

    /**
     * Merge the array into the notification context.
     *
     * @param  string|array  $context
     * @return $this
     */
    public function context($context, $value = null)
    {
        if(is_array($context)) {
            $this->context->merge($context);
        }
        else {
            $this->context->set($context, $value);
        }

        return $this;
    }
    
    /**
     * Get the request parameters.
     *
     * @return array
     */
    public function getRequestParams()
    {        
        return array_filter(array_merge([
            'Action' => static::ACTION,
            'CampaignId' => $this->campaignId,
            'ApiKey' => $this->apiKey,
            'AccountId' => $this->accountId,
            'RecipientXml' => $this->recipient->toXml()->toString(),
            'ContextDataXml' => $this->context->toXml()->toString(),
            'NotificationEmailAddress' => $this->notificationEmailAddress,
            'CorrelationId' => $this->correlationId
        ], $this->getRequestAttachmentParams()));
    }

    /**
     * Get the request parameters.
     *
     * @return array
     */
    public function getRequestAttachmentParams()
    {
        return array_reduce(
            $this->attachments,
            function($carry, $value) use (&$count) {
                $count++;

                return array_merge($carry, array_combine(
                    array_map(function($k) use ($count) {
                        return $k . '.' . $count;
                    }, array_keys($value)),
                    $value
                ));
            },
            []
        );
    }

    /**
     * Set the email recipient of the notification.
     *
     * @param  mixed  $recipient
     * @return $this
     */
    public function recipient($recipient)
    {
        if(is_callable($recipient)) {
            $this->recipient = $recipient(new Recipient);
        }
        else if($recipient instanceof User) {
            $this->recipient = new Recipient($recipient->toArray());
        }
        else {
            $this->recipient = new Recipient($recipient);
        }

        return $this;
    }

    /**
     * An alias for the recipient method.
     *
     * @param  string  $recipient
     * @return $this
     */
    public function to(...$args)
    {
        return $this->recipient(...$args);
    }

    /**
     * Set the notication email recipient of the notification.
     *
     * @param  string  $notificationEmailAddress
     * @return $this
     */
    public function notificationEmail($notificationEmailAddress)
    {
        $this->notificationEmailAddress = $notificationEmailAddress;

        return $this;
    }

    /**
     * Set the correlation id of the notification.
     *
     * @param  string  $correlationId
     * @return $this
     */
    public function correlate($correlationId)
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    /**
     * Cast the message as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return collect((array) $this)
            ->map(function($value) {
                if($value instanceof Arrayable) {
                    return $value->toArray();
                }

                return $value;
            })
            ->all();
    }
    
    /**
     * Cast the message as XML.
     *
     * @return \Actengage\LaravelMessageGears\Xml
     */
    public function toXml()
    {
        return Xml::fromArray($this->toArray());
    }
}