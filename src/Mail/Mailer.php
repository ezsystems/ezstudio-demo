<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Mail;

use App\Event\SendEmailEvent;
use App\Values\Email;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Mailer implements MailerInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        Swift_Mailer $mailer,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->mailer = $mailer;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function send(Email $email): int
    {
        $message = new Swift_Message(
            $email->getSubject(),
            $email->getBody()
        );
        $message
            ->setFrom($email->getSender())
            ->setTo($email->getRecipients());

        $this->eventDispatcher->dispatch(
            new SendEmailEvent($email)
        );

        return $this->mailer->send($message);
    }

    /**
     * {@inheritDoc}
     */
    public function create(string $subject, string $body, string $sender, array $recipients): Email
    {
        return new Email($subject,  $body,  $sender, $recipients);
    }
}
