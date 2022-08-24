<?php

namespace Actengage\MessageGears;

use Illuminate\Support\Arr;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\RawMessage;
use Throwable;

class MessageGearsTransport extends AbstractTransport
{
    /**
     * Create the MessageGearsTransport instance.
     * 
     * @param Cloud $api
     * @param string $campaignId
     * @param mixed ...$args
     */
    public function __construct(
        protected Cloud $api,
        protected string $campaignId,
        ...$args
    ) {
        parent::__construct(...$args);    
    }

    /**
     * Get the string representation of the transport.
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'mg';
    }

    /**
     * {@inheritDoc}
     */
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $this->api->authenticate()->post(['v5.1/campaign/transactional/%s', $this->campaignId], [
            'json' => array_filter([
                'accountId' => $this->api->accountId,
                'context' => [
                    'data' => [
                        'SubjectLine' => $email->getSubject(),
                        'TextContent' => $email->getTextBody(),
                        'HtmlContent' => $email->getHtmlBody(),
                        'FromAddress' => $this->getFromAddress($message),
                        'FromName' => $this->getFromName($message),
                    ],
                    'format' => 'JSON'
                ],
                'recipient' => [
                    'data' => [
                        'EmailAddress' => $to = $email->getTo()[0]->getAddress()
                    ],
                    'format' => 'JSON'
                ],
            ]),
        ]);
    }

    protected function getFromAddress(SentMessage $message): ?string
    {
        if($from = Arr::get($message->getOriginalMessage()->getFrom(), 0)) {
            return $from->getAddress();
        }
    }

    protected function getFromName(SentMessage $message): ?string
    {
        if($from = Arr::get($message->getOriginalMessage()->getFrom(), 0)) {
            return $from->getName();
        }
    }
}