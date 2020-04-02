<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Mail;

use App\ValueObject\Message;

interface MailerInterface
{
    /**
     * @param \App\ValueObject\Message $message
     *
     * @return int
     */
    public function send(Message $message): int;
}
