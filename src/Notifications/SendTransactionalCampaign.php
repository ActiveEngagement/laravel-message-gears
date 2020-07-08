<?php

namespace Actengage\LaravelMessageGears\Notifications;

use Actengage\LaravelMessageGears\Exceptions\MissingRecipient;
use Actengage\LaravelMessageGears\Messages\TransactionalCampaignSubmit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendTransactionalCampaign extends Notification implements ShouldQueue
{
    use Queueable;
    
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
        return [MessageGearsChannel::class];
    }
    
    /**
     * Cast the notification as a transaction campaign message.
     *
     * @param  array  $params
     * @return \Actengage\LaravelMessageGears\TransactionalCampaignSubmit;
     */
    public function toMessageGears($notifiable)
    {
        $message = new TransactionalCampaignSubmit($this->params);
        
        $email = $notifiable->routeNotificationFor('message_gears', $this);
        
        $message->recipient($email ?: $notifiable->email);

        if(!$message->recipient) {
            throw new MissingRecipient('The message has no recipient.');
        }

        return $message;
    }
}