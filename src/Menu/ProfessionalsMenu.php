<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Menu\CacheAware\MenuGeneratorInterface;
use App\Value\MenuQueryParameters;

final class ProfessionalsMenu extends MenuDecorator
{
    private const CACHE_KEY_MENU = 'app_professionals_listing_menu';
    private const MENU_ITEM_LIMIT = 400;
    private const MENU_CONTENT_TYPES = ['folder'];
    private const MENU_CONTENT_DEPTH = 5;

    /** @var \App\Menu\CacheAware\MenuGeneratorInterface */
    private $menuGenerator;

    public function __construct(
        MenuProviderInterface $menuProvider,
        MenuGeneratorInterface $menuGenerator
    ) {
        parent::__construct($menuProvider);

        $this->menuGenerator = $menuGenerator;
    }

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
}
