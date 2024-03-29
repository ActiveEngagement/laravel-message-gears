<?php

namespace Actengage\MessageGears;

use Illuminate\Support\Arr;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class MessageGearsTransport extends AbstractTransport
{
    /**
     * Create the MessageGearsTransport instance.
     *
     * @param  mixed  ...$args
     */
    public function __construct(
        protected Cloud $api,
        protected string $campaignId,
        protected array $jsonBody = [],
        ...$args
    ) {
        parent::__construct(...$args);
    }

    /**
     * Get the string representation of the transport.
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
            'json' => array_filter(array_merge_recursive([
                'accountId' => $this->api->accountId,
                'context' => [
                    'data' => [
                        'SubjectLine' => $email->getSubject(),
                        'TextContent' => $email->getTextBody(),
                        'HtmlContent' => $email->getHtmlBody(),
                        'FromAddress' => $this->getFromAddress($message),
                        'FromName' => $this->getFromName($message),
                    ],
                    'format' => 'JSON',
                ],
                'recipient' => [
                    'data' => [
                        'EmailAddress' => $email->getTo()[0]->getAddress(),
                    ],
                    'format' => 'JSON',
                ],
            ], $this->jsonBody)),
        ]);
    }

    protected function getFromAddress(SentMessage $message): ?string
    {
        if ($from = Arr::get($message->getOriginalMessage()->getFrom(), 0)) {
            return $from->getAddress();
        }
    }

    protected function getFromName(SentMessage $message): ?string
    {
        if ($from = Arr::get($message->getOriginalMessage()->getFrom(), 0)) {
            return $from->getName();
        }
    }
}
