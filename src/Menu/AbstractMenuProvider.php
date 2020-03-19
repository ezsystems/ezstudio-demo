<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Menu\CacheAware\MenuGeneratorInterface;

abstract class AbstractMenuProvider
{
    /** @var \App\Menu\CacheAware\MenuGeneratorInterface */
    protected $menuGenerator;

    public function __construct(MenuGeneratorInterface $menuGenerator)
    {
        $this->menuGenerator = $menuGenerator;
    }
}
