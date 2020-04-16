<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Values\MenuQueryParameters;

final class ProfessionalsMenu extends AbstractMenuProvider implements MenuProviderInterface
{
    private const MENU_NAME = 'professionals';
    private const CACHE_KEY_MENU = 'app_' . self::MENU_NAME . '_listing_menu';
    private const MENU_ITEM_LIMIT = 400;
    private const MENU_CONTENT_TYPES = ['folder'];
    private const MENU_CONTENT_DEPTH = 5;

    public function get(string $pathString, int $rootLocationId): array
    {
        return $this->menuGenerator->generate(
            new MenuQueryParameters(
                $pathString,
                $rootLocationId,
                self::MENU_CONTENT_TYPES,
                self::MENU_CONTENT_DEPTH,
                self::MENU_ITEM_LIMIT
            ),
            self::CACHE_KEY_MENU
        );
    }

    public function supports(string $type): bool
    {
        return self::MENU_NAME === $type;
    }
}
