# MessageGears

![PHP Composer](https://github.com/actengage/laravel-message-gears/workflows/PHP%20Composer/badge.svg)

This is an API wrapper for MessageGears specifically for Laravel. This package providers a fluent syntax for sending MessageGears requests and notifications in Laravel.

    composer require actengage/laravel-message-gears

## Config

Define the MessageGears config in the `config/services.php` file. Any value here is considered the global default and can be overriden on a per-request basis.

``` php
// config/services.php

return [
    'messagesgears' => [
        'api_key' => '...',
        'account_id' => '...',
        'campaign_id' => '...',
    ]
]
```

## Laravel Notification

Using the default notification is simple. Instantiate the notification and pass the parameters. Any parameters will override global or defaults values.

``` php
use Actengage\LaravelMessageGears\Notifications\SendTransactionalCampaign;

$user = new User();
$user->email = 'test@test.com';
$user->save();
$user->notify(new SendTransactionalCampaign([
    'campaignId' => 'CAMPAIGN_ID'
));
```

## Submit Transactional Campaign

Manually send a transactional campaign using the service provider.

``` php
app('messagegears')->submitTransactionCampaign([
    'campaignId' => 'CAMPAIGN_ID',
    'recipient' => [
        'email' => 'test@test.com'
    ]
]);
```

## Fluent Message Builder

You can also instantiate the fluent message builder and send the message directly. 

``` php
use Actengage\LaravelMessageGears\TransactionalCampaignSubmit;

$message = (new TransactionalCampaignSubmit)
    ->accountId(1)
    ->apiKey('API_KEY')
    ->to('test@test.com')
    ->context('some.nested.context', true);

app('messagegears')->submitTransactionalCampaign($message)
```

## Custom Notifications

This is an example of notification. The `toTransactionalCampaign` campaign must return an instance of `Actengage\LaravelMessageGears\TransactionalCampaignSubmit`.

``` php
<?php

namespace Actengage\LaravelMessageGears\Notifications;

use Illuminate\Notifications\Notification;
use Actengage\LaravelMessageGears\TransactionalCampaignChannel;
use Actengage\LaravelMessageGears\Messages\TransactionalCampaignSubmit;

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
     * Cast the notification as a transactional campaign message.
     *
     * @param  array  $params
     * @return \Actengage\LaravelMessageGears\TransactionalCampaignSubmit
     */
    public function toTransactionalCampaign($notifiable)
    {
        return new TransactionalCampaignSubmit($this->params);
    }
}
```