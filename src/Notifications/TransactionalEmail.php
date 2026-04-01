<?php

declare(strict_types=1);

namespace Actengage\MessageGears\Notifications;

use Actengage\MessageGears\Concerns\HasCampaign;
use Actengage\MessageGears\Concerns\HasContext;
use Actengage\MessageGears\Concerns\HasSender;
use Actengage\MessageGears\Context;
use Actengage\MessageGears\Facades\Cloud;
use Actengage\MessageGears\MessageGearsChannel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use InvalidArgumentException;
use Override;

class TransactionalEmail extends Notification
{
    use HasCampaign;
    use HasContext;
    use HasSender;

    /**
     * The category.
     */
    public ?string $category = null;

    /**
     * The correlation id.
     */
    public ?string $correlationId = null;

    /**
     * The latest send time.
     */
    public ?Carbon $latestSendTime = null;

    /**
     * An email address which will receive notifications on job errors.
     */
    public ?string $notificationEmailAddress = null;

    /**
     * Create an instance.
     */
    public function __construct()
    {
        $this->accountId(Cloud::instance()->accountId);
        $this->context(new Context);
    }

    /**
     * Set the `category` property.
     */
    public function category(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set the `correlationId` property.
     */
    public function correlationId(string $correlationId): self
    {
        $this->correlationId = $correlationId;

        return $this;
    }

    /**
     * Set the `latestSendTime` property.
     */
    public function latestSendTime(Carbon|string $latestSendTime): self
    {
        $this->latestSendTime = Date::make($latestSendTime);

        return $this;
    }

    /**
     * Set the `notificationEmailAddress` property.
     */
    public function notificationEmailAddress(string $notificationEmailAddress): self
    {
        $this->notificationEmailAddress = $notificationEmailAddress;

        return $this;
    }

    /**
     * Get the notification channels.
     *
     * @return class-string<MessageGearsChannel>
     */
    public function via(mixed $notifiable): string
    {
        return MessageGearsChannel::class;
    }

    /**
     * Get the endpoint URI for the notification.
     */
    #[Override]
    public function uri(): string
    {
        return Cloud::uri('v5.1/campaign/transactional/%s', $this->campaignId);
    }

    /**
     * Send the notification.
     */
    #[Override]
    public function send(object $notifiable): void
    {
        throw_unless(isset($this->campaignId), InvalidArgumentException::class, 'The campaign ID is required to send transactional emails.');

        Cloud::authenticate()->post($this->uri(), [
            'headers' => $this->headers,
            'json' => array_filter([
                'accountId' => $this->accountId,
                'campaignVersion' => $this->campaignVersion,
                'configuration' => array_filter([
                    'category' => $this->category,
                    'correlationId' => $this->correlationId,
                    'latestSendTime' => $this->latestSendTime,
                    'notificationEmailAddress' => $this->notificationEmailAddress,
                ]),
                'context' => (
                    ($data = $this->context->toArray()) === [] ? [] : [
                        'data' => $data,
                        'format' => 'JSON',
                    ]
                ),
                'recipient' => [
                    'data' => $this->recipient($notifiable)->toArray(),
                    'format' => 'JSON',
                ],
            ]),
        ]);
    }
}
