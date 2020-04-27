<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu\Item;

use App\Values\MenuItem;

interface ItemBuilderInterface
{
    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Location[] $locations
     * @param int $parentLocationId
     *
     * @return MenuItem[]
     */
    public function build(array &$locations, int $parentLocationId = 0): array;
}
