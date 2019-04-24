<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Value;

class BlockPreRenderValueObject extends PageBuilderBlockValueObject
{
    /** @var \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\RenderRequestInterface */
    public $renderRequest;
}
