<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Mail;

use App\ValueObject\Email;

interface MailerInterface
{
    /**
     * @param \App\ValueObject\Email $email
     */
    public function send(Email $email): int;

    /**
     * @return \App\ValueObject\Email
     */
    public function create(string $subject, string $body, string $sender, array $recipients): Email;
}
