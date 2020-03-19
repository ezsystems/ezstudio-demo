<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

interface MenuProviderInterface
{
    public function get(string $pathString, int $rootLocationId): array;

    public function supports(string $type): bool;
}
