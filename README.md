# MessageGears

This is an API wrapper for MessageGears specifically for Laravel. This package providers a fluent syntax for sending MessageGears requests and notifications in Laravel.

## Laravel Notification

``` php
use Actengage\LaravelMessageGears\SendTransactionalCampaign;

$user = new User();
$user->email = 'test@test.com';
$user->save();
$user->notify(new SendTransactionalCampaign([
    'accountId' => 'ACCOUNT_ID',
    'apiKey' => 'API_KEY',
    'campaignId' => 'CAMPAIGN_ID'
));
```

## Submit Transactional Campaign

Arrays will be cast into a fluent message builder.

``` php
app('messagegears')->submitTransactionCampaign([
    'accountId' => 'ACCOUNT_ID',
    'apiKey' => 'API_KEY',
    'campaignId' => 'CAMPAIGN_ID',
    'recipient' => [
        'email' => 'test@test.com'
    ]
]);
```

## Fluent Message Builder

Or you can instantiate the message builder and send the message directly. 

``` php
use Actengage\LaravelMessageGears\TransactionalCampaignMessage;

$message = (new TransactionalCampaignMessage)
    ->accountId(1)
    ->apiKey('API_KEY')
    ->to('test@test.com')
    ->context('some.nested.context', true);

app('messagegears')->submitTransactionalCampaign($message)
```