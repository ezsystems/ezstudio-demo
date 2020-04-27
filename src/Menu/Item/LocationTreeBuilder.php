<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu\Item;

use App\Values\MenuItem;

final class LocationTreeBuilder implements ItemBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(array &$locations, int $parentLocationId = 0): array
    {
        $branch = [];

        foreach ($locations as $location) {
            if ($location->parentLocationId !== $parentLocationId) {
                continue;
            }

            $item = new MenuItem(
                $location->content->getFieldValue('name')->text,
                $location->id,
                $location->content->id,
                self::build($locations, $location->id)
            );

            $branch[] = $item;

            unset($locations[$location->id]);
        }

        return $branch;
    }
}
