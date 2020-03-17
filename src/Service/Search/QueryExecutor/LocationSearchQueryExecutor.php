<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Service\Search\QueryExecutor;

use App\QueryType\MenuQueryType;
use App\Value\MenuQueryParameters;
use eZ\Publish\API\Repository\SearchService as SearchServiceInterface;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;

final class LocationSearchQueryExecutor
{
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

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchResult
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function getResults(MenuQueryParameters $queryParameters): SearchResult
    {
        $query = $this->menuQueryType->getQuery([
            'path_string' => $queryParameters->getPathString(),
            'included_content_type_identifier' => $queryParameters->getIncludedContentTypeIdentifiers(),
            'depth' => $queryParameters->getDepth(),
        ]);

        $query->limit = $queryParameters->getQueryLimit();

        return $this->searchService->findLocations($query);
    }
}
