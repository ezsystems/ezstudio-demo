<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Menu\MenuProviderStrategy;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

final class MenuController extends Controller
{
    private $provider;

    public function __construct(MenuProviderStrategy $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @throws \Twig\Error\Error
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function getMenuAction(
        string $type,
        string $template,
        string $pathString,
        int $rootLocationId,
        ?int $currentLocationId
    ): Response {
        $response = new Response();
        $response->setVary('X-User-Hash');

        return $this->render($template, [
            'menuItems' => $this->provider->get($type, $pathString, $rootLocationId),
            'currentLocationId' => $currentLocationId,
        ],
            $response
        );
    }
}
