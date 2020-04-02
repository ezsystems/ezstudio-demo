<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\ValueObject;

final class MailMessage extends Message
{
    /** @var string */
    private $sender;

    /** @var string */
    private $subject;

    public function __construct(string $subject, string $body, string $sender, array $recipients)
    {
        parent::__construct($body, $recipients);

        $this->sender = $sender;
        $this->recipients = $recipients;
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->message;
    }

}
