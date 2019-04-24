<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Value;

class PageBuilderBlockValueObject
{
    /** @var \EzSystems\EzPlatformPageFieldType\FieldType\LandingPage\Model\BlockValue $blockValue */
    public $blockValue;

    /** @var string */
    public $contentTypeIdentifier;

    /** @var array */
    public $conditions;
}
