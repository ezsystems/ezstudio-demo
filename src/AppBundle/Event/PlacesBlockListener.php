<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Event;

use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\BlockRenderEvents;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\Event\PreRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use eZ\Publish\API\Repository\Values\Content\Query;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\SearchService;

class PlacesBlockListener implements EventSubscriberInterface
{
    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /**
     * PlacesBlockListener constructor.
     *
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \eZ\Publish\API\Repository\SearchService $searchService
     */
    public function __construct(
        ContentService $contentService,
        LocationService $locationService,
        SearchService $searchService
    ) {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->searchService = $searchService;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            BlockRenderEvents::getBlockPreRenderEventName('places') => 'onBlockPreRender',
        ];
    }

    /**
     * @param \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\Event\PreRenderEvent $event
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function onBlockPreRender(PreRenderEvent $event)
    {
        $blockValue = $event->getBlockValue();
        $renderRequest = $event->getRenderRequest();

        $parameters = $renderRequest->getParameters();

        $contentIdAttribute = $blockValue->getAttribute('contentId');
        $contentInfo = $this->contentService->loadContentInfo($contentIdAttribute->getValue());
        $location = $this->locationService->loadLocation($contentInfo->mainLocationId);

        $query = new Query();
        $query->query = new Criterion\LogicalAnd(
            [
                new Criterion\Subtree($location->pathString),
                new Criterion\ContentTypeIdentifier('place'),
                new Criterion\Visibility(Criterion\Visibility::VISIBLE),
            ]
        );

        $searchHits = $this->searchService->findContent($query)->searchHits;

        $contentArray = [];
        foreach ($searchHits as $searchHit) {
            $content = $searchHit->valueObject;
            $contentArray[] = $content;
        }

        $parameters['contentArray'] = $contentArray;
        $parameters['location'] = $location;

        $renderRequest->setParameters($parameters);
    }
}
