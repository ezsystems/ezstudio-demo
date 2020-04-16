<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Search;

use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;

final class SearchResultLocationExtractor
{
    public static function extract(SearchResult $locationSearchResults): array
    {
        $locations = [];

        foreach ($locationSearchResults->searchHits as $hit) {
            $location = $hit->valueObject;
            $locations[$location->id] = $location;
        }

        return $locations;
    }
}
