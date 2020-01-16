<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\QueryType;

use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\Core\QueryType\QueryType as QueryTypeInterface;

final class BlockQueryType implements QueryTypeInterface
{
    /**
     * @param array $parameters
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Query
     */
    public function getQuery(array $parameters = []): Query
    {
        $query = new Query();

        $criteria = [
            new Criterion\Visibility(Criterion\Visibility::VISIBLE)
        ];

        if (isset($parameters['locationId'])) {
            $criteria[] = new Criterion\ParentLocationId($parameters['locationId']);
        }

        if (isset($parameters['contentTypeIdentifier'])) {
            $criteria[] = new Criterion\ContentTypeIdentifier($parameters['contentTypeIdentifier']);
        }

        if (isset($parameters['additionalConditions']) && is_array($parameters['additionalConditions'])) {
            $criteria = array_merge($criteria, $parameters['additionalConditions']);
        }

        if (isset($parameters['limit'])) {
            $query->limit = $parameters['limit'];
        }

        $query->query = new Criterion\LogicalAnd($criteria);

        return $query;
    }

    /** @return array */
    public function getSupportedParameters(): array
    {
        return [
            'locationId',
            'contentTypeIdentifier',
            'limit',
            'additionalConditions'
        ];
    }

    /** @return string */
    public static function getName(): string
    {
        return 'App:BlockQueryType';
    }
}
