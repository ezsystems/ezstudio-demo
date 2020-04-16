<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Search;

use App\Values\QueryParameters;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;

interface QueryExecutorInterface
{
    /**
     * @param \App\Values\QueryParameters $queryParameters
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Search\SearchResult
     */
    public function getResults(QueryParameters $queryParameters): SearchResult;
}
