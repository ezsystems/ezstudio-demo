<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Menu;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use Knp\Menu\Util\MenuManipulator;

class ReorderMenuListener
{
    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event
     */
    public function moveTagsToLast(ConfigureMenuEvent $event): void
    {
        $menu = $event->getMenu();

        if (!$menu->getChild('eztags')) {
            return;
        }

        $manipulator = new MenuManipulator();
        $manipulator->moveToLastPosition($menu['eztags']);
    }
}
