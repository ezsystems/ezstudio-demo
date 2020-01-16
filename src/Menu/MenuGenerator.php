<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Service\Cache\CacheServiceInterface;
use App\Service\Search\QueryExecutor\LocationSearchQueryExecutor;
use App\Service\Search\SearchResultLocationExtractor;
use App\Tree\LocationTreeBuilder;
use App\Tree\Values\MenuItem;

final class MenuGenerator
{
    const CACHE_KEY_MENU = 'app_professionals_listing_menu';

    /** @var \App\Service\Cache\CacheServiceInterface */
    private $cacheService;

    /** @var \App\Service\Search\QueryExecutor\LocationSearchQueryExecutor */
    private $executor;

    public function __construct(
        CacheServiceInterface $cacheService,
        LocationSearchQueryExecutor $executor
    ) {
        $this->cacheService = $cacheService;
        $this->executor = $executor;
    }

    /**
     * @return MenuItem[]
     */
    public function generate(string $pathString, int $rootLocationId): array
    {
        $item = $this->cacheService->getItem(self::CACHE_KEY_MENU);

        if ($item->isHit()) {
            return $item->get();
        }

        $locationSearchResults = $this->executor->getResults($pathString);
        $menuItems = SearchResultLocationExtractor::extract($locationSearchResults);
        $menu = LocationTreeBuilder::build($menuItems, $rootLocationId);

        $item->expiresAfter((int) $this->cacheService->getCacheExpirationTime());
        $item->set($menu);
        $item->tag('location-' . $rootLocationId);

        $this->cacheService->save($item);

        return $menu;
    }
}
