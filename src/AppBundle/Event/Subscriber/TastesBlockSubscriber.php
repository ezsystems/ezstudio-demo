<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Event\Subscriber;

use AppBundle\Event\AbstractBlockEvent;
use AppBundle\Value\BlockPreRenderValueObject;
use AppBundle\Value\BlockResponseValueObject;
use EzSystems\EzPlatformPageFieldType\Event\BlockResponseEvent;
use EzSystems\EzPlatformPageFieldType\Event\BlockResponseEvents;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\BlockRenderEvents;
use EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\Event\PreRenderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TastesBlockSubscriber extends AbstractBlockEvent implements EventSubscriberInterface
{
    private const BLOCK_IDENTIFIER = 'tastes';
    private const CONTENT_TYPE_IDENTIFIER = 'article';

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BlockRenderEvents::getBlockPreRenderEventName(self::BLOCK_IDENTIFIER) => 'onBlockPreRender',
            BlockResponseEvents::getBlockResponseEventName(self::BLOCK_IDENTIFIER) => 'onBlockResponse'
        ];
    }

    /**
     * @param \EzSystems\EzPlatformPageFieldType\FieldType\Page\Block\Renderer\Event\PreRenderEvent $event
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function onBlockPreRender(PreRenderEvent $event): void
    {
        $blockPreRender = new BlockPreRenderValueObject();
        $blockPreRender->renderRequest = $event->getRenderRequest();
        $blockPreRender->blockValue = $event->getBlockValue();
        $blockPreRender->contentTypeIdentifier = self::CONTENT_TYPE_IDENTIFIER;

        $this->setPreRenderParameters($blockPreRender);
    }

    /**
     * @param \EzSystems\EzPlatformPageFieldType\Event\BlockResponseEvent $event
     */
    public function onBlockResponse(BlockResponseEvent $event): void
    {
        $blockResponse = new BlockResponseValueObject();
        $blockResponse->response = $event->getResponse();
        $blockResponse->blockValue = $event->getBlockValue();
        $blockResponse->contentTypeIdentifier = self::CONTENT_TYPE_IDENTIFIER;

        $this->processResponse($blockResponse);
    }
}
