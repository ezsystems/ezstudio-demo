<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event;

use App\ValueObject\MailMessage;
use Symfony\Contracts\EventDispatcher\Event;

final class SendMailEvent extends Event
{
    /** @var \App\ValueObject\MailMessage */
    private $message;

    public function __construct(MailMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @return \App\ValueObject\MailMessage
     */
    public function getMessage(): MailMessage
    {
        return $this->message;
    }
}
