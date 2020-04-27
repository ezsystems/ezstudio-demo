<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu\CacheAware;

use App\Menu\Item\ItemBuilderInterface;
use App\Values\MenuQueryParameters;

interface MenuGeneratorInterface
{
    /**
     * @param \App\Values\MenuQueryParameters $queryParameters
     * @param \App\Menu\Item\ItemBuilderInterface $builder
     * @param string $key
     *
     * @return array
     */
    public function generate(MenuQueryParameters $queryParameters, ItemBuilderInterface $builder, string $key): array;
}
