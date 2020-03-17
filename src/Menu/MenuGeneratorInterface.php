<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Value\MenuQueryParameters;

interface MenuGeneratorInterface
{
    /**
     * @return \App\Tree\Values\MenuItem[]
     */
    public function fromCache(MenuQueryParameters $queryParameters, string $cacheKey): array;

    /**
     * @return \App\Tree\Values\MenuItem[]
     */
    public function generate(MenuQueryParameters $queryParameters): array;
}
