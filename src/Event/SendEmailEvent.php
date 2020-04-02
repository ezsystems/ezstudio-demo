<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event;

use App\ValueObject\Email;
use Symfony\Contracts\EventDispatcher\Event;

final class SendEmailEvent extends Event
{
    /** @var \App\ValueObject\Email */
    private $message;

    public function __construct(Email $message)
    {
        $this->message = $message;
    }

    /**
     * @return \App\ValueObject\Email
     */
    public function getEmail(): Email
    {
        return $this->message;
    }
}
