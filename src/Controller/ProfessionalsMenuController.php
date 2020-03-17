<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Value\MenuQueryParameters;
use Symfony\Component\HttpFoundation\Response;

final class ProfessionalsMenuController extends AbstractMenuController
{
    private const CACHE_KEY_MENU = 'app_professionals_listing_menu';
    private const MENU_ITEM_LIMIT = 400;
    private const MENU_CONTENT_TYPES = ['folder'];
    private const MENU_CONTENT_DEPTH = 5;

    /**
     * @throws \Twig\Error\Error
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function getMenuAction(
        string $template,
        string $pathString,
        int $rootLocationId,
        ?int $currentLocationId
    ): Response {
        $menuItems = $this->getMenuGenerator()->fromCache(
            new MenuQueryParameters(
                $pathString,
                $rootLocationId,
                self::MENU_CONTENT_TYPES,
                self::MENU_CONTENT_DEPTH,
                self::MENU_ITEM_LIMIT
            ),
            self::CACHE_KEY_MENU
        );

        $response = new Response();
        $response->setVary('X-User-Hash');

        return $this->render($template, [
                'menuItems' => $menuItems,
                'currentLocationId' => $currentLocationId,
            ],
            $response
        );
    }
}
