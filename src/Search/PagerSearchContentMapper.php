<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Search;

use Pagerfanta\Pagerfanta;

final class PagerSearchContentMapper
{
    /**
     * @param \Pagerfanta\Pagerfanta $pagerfanta
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content[]
     */
    public function map(Pagerfanta $pagerfanta): array
    {
        $data = [];

        /** @var \eZ\Publish\API\Repository\Values\Content\Search\SearchHit $searchHit */
        foreach ($pagerfanta as $searchHit) {

            /** @var \eZ\Publish\API\Repository\Values\Content\Content $content */
            $content = $searchHit->valueObject;

            $data[] = $content;
        }

        return $data;
    }
}
