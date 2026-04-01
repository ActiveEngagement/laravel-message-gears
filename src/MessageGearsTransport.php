<?php

declare(strict_types=1);

namespace Actengage\MessageGears;

use Override;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\MessageConverter;

class MessageGearsTransport extends AbstractTransport
{
    /**
     * Create the MessageGearsTransport instance.
     *
     * @param  array<string, mixed>  $jsonBody
     */
    public function __construct(
        protected Cloud $api,
        protected string $campaignId,
        protected array $jsonBody = [],
        ?EventDispatcherInterface $dispatcher = null,
    ) {
        parent::__construct($dispatcher);
    }

    /**
     * Get the string representation of the transport.
     */
    #[Override]
    public function __toString(): string
    {
        return 'mg';
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function doSend(SentMessage $message): void
    {
        /** @var Message $originalMessage */
        $originalMessage = $message->getOriginalMessage();
        $email = MessageConverter::toEmail($originalMessage);

        $this->api->authenticate()->post(['v5.1/campaign/transactional/%s', $this->campaignId], [
            'json' => array_filter(array_merge_recursive([
                'accountId' => $this->api->accountId,
                'context' => [
                    'data' => [
                        'SubjectLine' => $email->getSubject(),
                        'TextContent' => $email->getTextBody(),
                        'HtmlContent' => $email->getHtmlBody(),
                        'FromAddress' => $this->getFromAddress($email),
                        'FromName' => $this->getFromName($email),
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

    protected function getFromAddress(Email $email): ?string
    {
        /** @var array<int, Address> $from */
        $from = $email->getFrom();

        return isset($from[0]) ? $from[0]->getAddress() : null;
    }

    protected function getFromName(Email $email): ?string
    {
        /** @var array<int, Address> $from */
        $from = $email->getFrom();

        return isset($from[0]) ? $from[0]->getName() : null;
    }
}
