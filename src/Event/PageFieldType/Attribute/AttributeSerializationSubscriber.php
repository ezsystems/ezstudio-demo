<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event\PageFieldType\Attribute;

use EzSystems\EzPlatformPageFieldType\Event\AttributeSerializationEvent;
use EzSystems\EzPlatformPageFieldType\Event\PageEvents;
use EzSystems\EzPlatformPageFieldType\ScheduleBlock\Item\Item;
use EzSystems\EzPlatformPageFieldType\ScheduleBlock\Timeline\Event\AbstractEvent;
use EzSystems\EzPlatformPageFieldType\ScheduleBlock\Timeline\Event\ItemAddedEvent;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function is_array;

final class AttributeSerializationSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PageEvents::ATTRIBUTE_SERIALIZATION => ['onAttributeSerialization', 20],
        ];
    }

    /**
     * @param \EzSystems\EzPlatformPageFieldType\Event\AttributeSerializationEvent $event
     */
    public function onAttributeSerialization(AttributeSerializationEvent $event): void
    {
        if ($event->getAttributeIdentifier() !== 'events') {
            return;
        }

        $deserializedValue = $event->getDeserializedValue();

        if ($deserializedValue === null || !is_array($deserializedValue)) {
            return;
        }

        foreach ($deserializedValue as $key => $timelineEvent) {
            if (!$timelineEvent instanceof ItemAddedEvent) {
                continue;
            }

            if (empty($timelineEvent->getId())) {
                $timelineEvent->setId(sprintf('%s%s', AbstractEvent::IDENTIFIER_PREFIX, Uuid::uuid4()));
            }

            $item = $timelineEvent->getItem();

            if (empty($item->getId())) {
                $item->setId(sprintf('%s%s', Item::IDENTIFIER_PREFIX, Uuid::uuid4()));
            }

            $deserializedValue[$key] = $timelineEvent;
        }

        $event->setDeserializedValue($deserializedValue);
    }
}
