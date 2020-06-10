<?php

namespace Actengage\LaravelMessageGears;

use Illuminate\Notifications\Notification;

class SendTransactionalCampaign extends Notification
{
    /**
     * The notification params.
     *
     * @var  array  $params
     */
    public $params;

    /**
     * The notification constructor.
     *
     * @param  array  $params
     * @return void
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;    
    }
    
    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [TransactionalCampaignChannel::class];
    }

    /**
     * Cast the notification as a transaction campaign message.
     *
     * @param  array  $params
     * @return void
     */
    public function toTransactionalCampaign($notifiable)
    {
        return new TransactionalCampaignMessage($this->params);
    }
}