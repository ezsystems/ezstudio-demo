<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\QueryType\MenuQueryType;
use eZ\Publish\API\Repository\SearchService;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

final class MenuController
{
    /** @var \Twig\Environment */
    private $twig;

    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /** @var \App\QueryType\MenuQueryType */
    private $menuQueryType;

    /** @var int */
    private $topMenuParentLocationId;

    /** @var array */
    private $topMenuContentTypeIdentifier;

    public function __construct(
        TwigEnvironment $twig,
        SearchService $searchService,
        MenuQueryType $menuQueryType,
        int $topMenuParentLocationId,
        array $topMenuContentTypeIdentifier
    ) {
        $this->twig = $twig;
        $this->searchService = $searchService;
        $this->menuQueryType = $menuQueryType;
        $this->topMenuParentLocationId = $topMenuParentLocationId;
        $this->topMenuContentTypeIdentifier = $topMenuContentTypeIdentifier;
    }

    /**
     * Renders top menu with child items.
     *
     * @throws \Twig\Error\Error
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function getChildNodesAction(string $template, ?string $pathString = null): Response
    {
        $query = $this->menuQueryType->getQuery([
            'parent_location_id' => $this->topMenuParentLocationId,
            'included_content_type_identifier' => $this->topMenuContentTypeIdentifier,
        ]);

        $locationSearchResults = $this->searchService->findLocations($query);

        $menuItems = [];
        foreach ($locationSearchResults->searchHits as $hit) {
            $menuItems[] = $hit->valueObject;
        }

        $pathArray = $pathString ? explode('/', $pathString) : [];

        $response = new Response();
        $response->setVary('X-User-Hash');

        $response->setContent(
            $this->twig->render(
                $template,
                [
                    'menuItems' => $menuItems,
                    'pathArray' => $pathArray,
                ]
            )
        );

        return $response;
    }
}
