<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\ValueObject;

final class Email extends \Swift_Message
{
    /** @var string */
    private $sender;

    /** @var array */
    private $recipients;

    public function __construct(string $subject, string $body, string $sender, array $recipients)
    {
        parent::__construct($subject, $body);

        $this->sender = $sender;
        $this->recipients = $recipients;
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
}
