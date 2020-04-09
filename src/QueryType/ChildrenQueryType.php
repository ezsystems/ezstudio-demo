<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\QueryType;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\Core\QueryType\QueryType;

final class ChildrenQueryType implements QueryType
{
    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Query
     */
    public function getQuery(array $parameters = []): Query
    {
        $query = new Query();
        $query->filter = new Query\Criterion\LogicalAnd(
            $this->prepareCriteria($parameters)
        );
        $query->limit = $parameters['limit'] ?? 10;
        $query->sortClauses = [new Query\SortClause\DatePublished(
            $parameters['sort_clauses'] ?? Query::SORT_DESC
        )];

        if (!empty($parameters['depth'])) {
            return new LocationQuery([
                'filter' => $query->filter,
                'sortClauses' => $query->sortClauses,
                'limit' => $query->limit,
            ]);
        }

        return $query;
    }

    public static function getName(): string
    {
        return 'App:Children';
    }

    public function getSupportedParameters(): array
    {
        return [
            'parent_location_id',
            'limit',
        ];
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Query\Criterion[]
     */
    private function prepareCriteria(array $parameters = []): array
    {
        $criteria = [
            new Query\Criterion\Visibility(Query\Criterion\Visibility::VISIBLE),
        ];

        if (!empty($parameters['path_string'])) {
            $criteria[] = new Query\Criterion\Subtree($parameters['path_string']);
        }

        if (!empty($parameters['depth'])) {
            $criteria[] = new Query\Criterion\Location\Depth(Query\Criterion\Operator::LTE, $parameters['depth']);
        }

        if (!empty($parameters['parent_location_id'])) {
            $criteria[] = new Query\Criterion\ParentLocationId($parameters['parent_location_id']);
        } else {
            // TODO: check if needed
            //$criteria[] = new Query\Criterion\MatchNone();
        }

        if (!empty($parameters['included_content_type_identifier'])) {
            $criteria[] = new Query\Criterion\ContentTypeIdentifier($parameters['included_content_type_identifier']);
        }

        if (!empty($parameters['main_location_only'])) {
            $criteria[] = new Query\Criterion\Location\IsMainLocation(
                Query\Criterion\Location\IsMainLocation::MAIN
            );
        }

        return $criteria;
    }

}
