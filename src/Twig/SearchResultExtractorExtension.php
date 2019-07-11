<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Twig;

use eZ\Publish\API\Repository\Values\Content\Search\SearchHit;
use eZ\Publish\API\Repository\Values\Content\Search\SearchResult;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig helper for extract SearchResult object and returns array
 */
class SearchResultExtractorExtension extends AbstractExtension
{
    /**
     * Returns the name of the extension.
     *
     * @return string the extension name
     */
    public function getName(): string
    {
        return 'app.search_result_extract';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_search_result_extract', [$this, 'extractSearchResult'])
        ];
    }

    /**
     * Returns extracted SearchResult object as array
     *
     * @param SearchResult $searchResult
     * @return array
     */
    public function extractSearchResult(SearchResult $searchResult): array
    {
        return array_map(function (SearchHit $searchHit) {
            return $searchHit->valueObject;
        }, $searchResult->searchHits);
    }
}
