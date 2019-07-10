<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Value;

class BlockResponseValueObject extends PageBuilderBlockValueObject
{
    /** @var \Symfony\Component\HttpFoundation\Response */
    public $response;
}
