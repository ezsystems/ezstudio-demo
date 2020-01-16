<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\QueryType;

use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion\Operator as CriterionOperator;
use eZ\Publish\API\Repository\Values\Content\Query\SortClause;
use eZ\Publish\Core\QueryType\QueryType;

final class MenuQueryType implements QueryType
{
    /** @var string[] */
    private $languages;

    /**
     * @param string[] $languages
     */
    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }

    /**
     * @param string[] $parameters
     */
    public function getQuery(array $parameters = []): LocationQuery
    {
        $criteria = [
            new Query\Criterion\Visibility(Query\Criterion\Visibility::VISIBLE),
            new Query\Criterion\ContentTypeIdentifier($parameters['included_content_type_identifier']),
            new Query\Criterion\LanguageCode($this->languages),
        ];

        if (!empty($parameters['path_string'])) {
            $criteria[] = new Query\Criterion\Subtree($parameters['path_string']);
        }

        if (!empty($parameters['parent_location_id'])) {
            $criteria[] = new Query\Criterion\ParentLocationId($parameters['parent_location_id']);
        }

        if (!empty($parameters['depth'])) {
            $criteria[] = new Query\Criterion\Location\Depth(CriterionOperator::LTE, $parameters['depth']);
        }

        $options = [
            'filter' => new Query\Criterion\LogicalAnd($criteria),
            'sortClauses' => [
                new SortClause\Location\Priority(LocationQuery::SORT_ASC),
            ],
        ];

        return new LocationQuery($options);
    }

    public static function getName(): string
    {
        return 'App:Menu';
    }

    /**
     * Returns array of required parameters.
     */
    public function getSupportedParameters(): array
    {
        return [
            'parent_location_id',
            'included_content_type_identifier',
        ];
    }
}
