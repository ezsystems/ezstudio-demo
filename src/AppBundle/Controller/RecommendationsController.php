<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace AppBundle\Controller;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use eZ\Publish\Core\MVC\Symfony\Routing\ChainRouter;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\SearchService;
use eZ\Publish\API\Repository\Values\Content\LocationQuery;
use eZ\Publish\API\Repository\ContentService;

class RecommendationsController
{
    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \eZ\Publish\Core\MVC\Symfony\Routing\ChainRouter */
    private $router;

    /** @var \Symfony\Bundle\TwigBundle\TwigEngine */
    private $templating;

    /** @var \eZ\Publish\API\Repository\Values\Content\Content[] */
    private $randomRecommendations = [];

    /**
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \eZ\Publish\API\Repository\SearchService $searchService
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \eZ\Publish\Core\MVC\Symfony\Routing\ChainRouter $router
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     */
    public function __construct(
        LocationService $locationService,
        SearchService $searchService,
        ContentService $contentService,
        ChainRouter $router,
        TwigEngine $templating
    ) {
        $this->locationService = $locationService;
        $this->searchService = $searchService;
        $this->contentService = $contentService;
        $this->router = $router;
        $this->templating = $templating;
    }

    /**
     * Displays template with random recommendations.
     *
     * @param string $template
     * @param array|string $selectedContentTypes
     * @param int $limit
     * @param bool $useCache
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showRandom(
        $template,
        $selectedContentTypes,
        $limit = 3,
        $useCache = true
    ) {
        if (!$useCache || empty($this->randomRecommendations)) {
            $this->randomRecommendations = $this->getRandomContent(
                $this->getQuery($selectedContentTypes),
                $limit
            );
        }

        return $this->templating->renderResponse(
            $template,
            [
                'randomRecommendations' => $this->randomRecommendations,
            ],
            new Response()
        );
    }

    /**
     * Returns random recommendations in JSON format.
     *
     * @param array|string $selectedContentTypes
     * @param int $limit
     * @param bool $useCache
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getRandomJSON(
        $selectedContentTypes,
        $limit = 3,
        $useCache = true
    ) {
        if (!$useCache || empty($this->randomRecommendations)) {
            $this->randomRecommendations = $this->getRandomContent(
                $this->getQuery($selectedContentTypes),
                $limit
            );
        }

        $randomContent = [];
        foreach ($this->randomRecommendations as $content) {
            $randomContent[] = [
                'name' => $content->contentInfo->name,
                'uri' => $this->router->generate('ez_urlalias', ['contentId' => $content->id]),
                'image' => $content->getFieldValue('image')->uri,
            ];
        }

        return new JsonResponse([
            'data' => $randomContent,
        ]);
    }

    /**
     * Returns limited and randomized array of Content objects based on given arguments.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\LocationQuery $query
     * @param int $limit
     *
     * @return array
     */
    private function getRandomContent(LocationQuery $query, $limit)
    {
        $results = $this->searchService->findLocations($query);

        shuffle($results->searchHits);

        $items = [];
        foreach ($results->searchHits as $item) {
            $items[] = $this->contentService->loadContentByContentInfo(
                $item->valueObject->contentInfo
            );

            if (count($items) == $limit) {
                break;
            }
        }

        return $items;
    }

    /**
     * Returns LocationQuery object based on given arguments.
     *
     * @param array|string $selectedContentTypes
     *
     * @return \eZ\Publish\API\Repository\Values\Content\LocationQuery
     */
    private function getQuery($selectedContentTypes)
    {
        $query = new LocationQuery();

        $query->query = new Criterion\LogicalAnd([
            new Criterion\Visibility(Criterion\Visibility::VISIBLE),
            new Criterion\ContentTypeIdentifier($selectedContentTypes),
        ]);

        return $query;
    }
}
