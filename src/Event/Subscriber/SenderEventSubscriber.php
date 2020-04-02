<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event\Subscriber;

use App\Event\SendMailEvent;
use App\Mail\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SenderEventSubscriber implements EventSubscriberInterface
{
    /** @var \App\Mail\MailerInterface */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            SendMailEvent::class => ['sendMail'],
        ];
    }

    /**
     * @param \App\Event\SendMailEvent $event
     */
    public function sendMail(SendMailEvent $event): void
    {
        $this->mailer->send($event->getMessage());
    }
}
