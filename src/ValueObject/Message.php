<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\ValueObject;

abstract class Message
{
    /** @var string */
    protected $message;

    /** @var array */
    protected $recipients;

    public function __construct(string $message, array $recipients)
    {
        $this->message = $message;
        $this->recipients = $recipients;
    }
}
