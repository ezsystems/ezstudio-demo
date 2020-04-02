<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Mail;

use App\ValueObject\Message;
use Swift_Mailer;
use Swift_Message;

final class Mailer implements MailerInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Message $message): int
    {
        $mail = new Swift_Message(
            $message->getSubject(),
            $message->getBody()
        );
        $mail
            ->setFrom($message->getSender())
            ->setTo($message->getRecipients());

        return $this->mailer->send($mail);
    }
}
