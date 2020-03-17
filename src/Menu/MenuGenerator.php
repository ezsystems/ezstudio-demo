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
use App\Value\MenuQueryParameters;

final class MenuGenerator implements MenuGeneratorInterface
{
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
     * @inheritDoc
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function fromCache(MenuQueryParameters $queryParameters, string $cacheKey): array
    {
        $item = $this->cacheService->getItem($cacheKey);

        if ($item->isHit()) {
            return $item->get();
        }

        $menu = $this->generate($queryParameters);

        $item->expiresAfter((int) $this->cacheService->getCacheExpirationTime());
        $item->set($menu);
        $item->tag('location-' . $queryParameters->getRootLocationId());

        $this->cacheService->save($item);

        return $menu;
    }

    /**
     * @inheritDoc
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function generate(MenuQueryParameters $queryParameters): array
    {
        $locationSearchResults = $this->executor->getResults($queryParameters);
        $menuItems = SearchResultLocationExtractor::extract($locationSearchResults);

        return LocationTreeBuilder::build($menuItems, $queryParameters->getRootLocationId());
    }
}
