<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Value\MenuQueryParameters;

abstract class MenuDecorator implements MenuProviderInterface
{
    /** @var \App\Menu\MenuProviderInterface */
    protected $menuProvider;

    public function __construct(MenuProviderInterface $menuProvider)
    {
        $this->menuProvider = $menuProvider;
    }

    public function get(string $pathString, int $rootLocationId): array
    {
        return $this->menuProvider->get($pathString, $rootLocationId);
    }
}
