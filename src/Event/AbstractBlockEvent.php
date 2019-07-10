<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event;

use App\Helper\ContentHelper;
use App\Helper\LocationHelper;
use App\Value\BlockPreRenderValueObject;
use App\Value\BlockResponseValueObject;
use App\Value\PageBuilderBlockValueObject;
use eZ\Publish\API\Repository\Exceptions\InvalidArgumentException;
use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Exceptions\UnauthorizedException;
use eZ\Publish\Core\QueryType\QueryType as QueryTypeInterface;
use EzSystems\PlatformHttpCacheBundle\Handler\TagHandler;

abstract class AbstractBlockEvent
{
    /** @var \App\Helper\LocationHelper */
    protected $locationHelper;

    /** @var \App\Helper\ContentHelper */
    protected $contentHelper;

    /** @var \eZ\Publish\Core\QueryType\QueryType */
    protected $queryType;

    /** @var \EzSystems\PlatformHttpCacheBundle\Handler\TagHandler */
    protected $tagHandler;

    /**
     * @param \App\Helper\LocationHelper $locationHelper
     * @param \App\Helper\ContentHelper $contentHelper
     * @param \eZ\Publish\Core\QueryType\QueryType $queryType
     * @param \EzSystems\PlatformHttpCacheBundle\Handler\TagHandler $tagHandler
     */
    public function __construct(
        LocationHelper $locationHelper,
        ContentHelper $contentHelper,
        QueryTypeInterface $queryType,
        TagHandler $tagHandler
    ) {
        $this->locationHelper = $locationHelper;
        $this->contentHelper = $contentHelper;
        $this->queryType = $queryType;
        $this->tagHandler = $tagHandler;
    }

    /**
     * @param \App\Value\PageBuilderBlockValueObject $blockValueObject
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    protected function setPreRenderParameters(PageBuilderBlockValueObject $blockValueObject): void
    {
        /** @var BlockPreRenderValueObject $blockValueObject */
        $contentArray = $this->getContentItems($blockValueObject);
        $parameters = $blockValueObject->renderRequest->getParameters();
        $parameters['contentArray'] = $contentArray;
        $parameters['location'] = $this->locationHelper->getLocationIdByContentId((int) $blockValueObject->blockValue->getAttribute('contentId')->getValue());

        $blockValueObject->renderRequest->setParameters($parameters);
    }

    /** @param \App\Value\PageBuilderBlockValueObject $blockValueObject */
    protected function processResponse(PageBuilderBlockValueObject $blockValueObject): void
    {
        /** @var BlockResponseValueObject $blockValueObject */
        if (!$blockValueObject->blockValue->getAttribute('contentId')) {
            return;
        }

        try {
            $contentItems = $this->getContentItems($blockValueObject);
        } catch (UnauthorizedException | InvalidArgumentException | NotFoundException $exception) {
            return;
        }

        $tags = [];

        foreach ($contentItems as $content) {
            $tags[] = 'relation-' . $content->id;
        }

        $this->tagHandler->addTags(array_unique($tags));
        $this->tagHandler->tagSymfonyResponse($blockValueObject->response);
    }

    /**
     * @param \App\Value\PageBuilderBlockValueObject $blockValueObject
     *
     * @return array
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    protected function getContentItems(PageBuilderBlockValueObject $blockValueObject): array
    {
        $query = $this->queryType->getQuery([
            'locationId' => $this->locationHelper->getLocationIdByContentId((int) $blockValueObject->blockValue->getAttribute('contentId')->getValue()),
            'contentTypeIdentifier' => $blockValueObject->contentTypeIdentifier,
            'limit' => (int) $blockValueObject->blockValue->getAttribute('limit')->getValue(),
            'additionalConditions' => $blockValueObject->conditions,
        ]);

        return $this->contentHelper->findContentItems($query);
    }
}
