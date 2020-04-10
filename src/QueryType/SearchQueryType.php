<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\QueryType;

use App\Form\Data\SearchData;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\SearchService as SearchServiceInterface;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\Core\QueryType\OptionsResolverBasedQueryType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchQueryType extends OptionsResolverBasedQueryType
{
    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /** @var string[] */
    private $searchCriterionContentTypes;

    public function __construct(
        SearchServiceInterface $searchService,
        array $searchCriterionContentTypes
    ) {
        $this->searchService = $searchService;
        $this->searchCriterionContentTypes = $searchCriterionContentTypes;
    }

    /**
     * {@inheritDoc}
     */
    protected function doGetQuery(array $parameters): Query
    {
        /** @var \App\Form\Data\SearchData $searchData */
        $searchData = $parameters['search_data'];

        $query = new Query();

        if (null !== $searchData->getQuery()) {
            $query->query = new Criterion\FullText($searchData->getQuery());
        }

        $criteria = $this->buildCriteria();

        if (!empty($criteria)) {
            $query->filter = new Criterion\LogicalAnd($criteria);
        }

        if (!$this->searchService->supports(SearchService::CAPABILITY_SCORING)) {
            $query->sortClauses[] = new SortClause\DateModified(Query::SORT_ASC);
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'search_data' => SearchData::class
        ]);

        $optionsResolver->addAllowedTypes('search_data' , SearchData::class);
    }

    /**
     * {@inheritDoc}
     */
    public static function getName(): string
    {
        return 'App:SearchQuery';
    }

    /**
     * @param \App\Form\Data\SearchData $searchData
     *
     * @return \eZ\Publish\API\Repository\Values\URL\Query\Criterion[]
     */
    private function buildCriteria(): array
    {
        return [
            new Query\Criterion\Visibility(Query\Criterion\Visibility::VISIBLE),
            new Query\Criterion\ContentTypeIdentifier($this->searchCriterionContentTypes),
        ];
    }
}
