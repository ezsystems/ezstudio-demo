<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu\CacheAware;

use App\Value\MenuQueryParameters;

interface MenuGeneratorInterface
{
    /**
     * @return \App\Tree\Values\MenuItem[]
     */
    public function generate(MenuQueryParameters $queryParameters, string $key): array;
}
