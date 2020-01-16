<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Service\Search\QueryExecutor;

use App\QueryType\MenuQueryType;
use eZ\Publish\API\Repository\SearchService as SearchServiceInterface;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;

final class LocationSearchQueryExecutor
{
    const MENU_ITEM_LIMIT = 400;
    const MENU_CONTENT_TYPES = ['folder'];
    const MENU_CONTENT_DEPTH = 5;

    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /** @var \App\QueryType\MenuQueryType */
    private $menuQueryType;

    public function __construct(
        SearchServiceInterface $searchService,
        MenuQueryType $menuQueryType
    ) {
        $this->searchService = $searchService;
        $this->menuQueryType = $menuQueryType;
    }

    public function getResults($pathString): SearchResult
    {
        $query = $this->menuQueryType->getQuery([
            'path_string' => $pathString,
            'included_content_type_identifier' => self::MENU_CONTENT_TYPES,
            'depth' => self::MENU_CONTENT_DEPTH
        ]);

        $query->limit = self::MENU_ITEM_LIMIT;

        return $this->searchService->findLocations($query);
    }
}
