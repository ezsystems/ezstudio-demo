<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Mail;

use App\Values\Email;

interface MailerInterface
{
    /**
     * @param \App\Values\Email $email
     *
     * @return int
     */
    public function send(Email $email): int;

    /**
     * @param string $subject
     * @param string $body
     * @param string $sender
     * @param array $recipients
     *
     * @return \App\Values\Email
     */
    public function create(string $subject, string $body, string $sender, array $recipients): Email;
}
