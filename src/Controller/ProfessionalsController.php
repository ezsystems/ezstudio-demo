<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Menu\MenuGenerator;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

final class ProfessionalsController
{
    /** @var \Twig\Environment */
    private $twig;

    /** @var \App\Menu\MenuGenerator */
    private $menuGenerator;

    public function __construct(
        TwigEnvironment $twig,
        MenuGenerator $menuGenerator
    ) {
        $this->twig = $twig;
        $this->menuGenerator = $menuGenerator;
    }

    /**
     * Renders left menu with child items.

     * @throws \Twig\Error\Error
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function getMenuAction(
        string $template,
        string $pathString,
        int $rootLocationId,
        ?int $currentLocationId
    ): Response {
        $menuItems = $this->menuGenerator->generate($pathString, $rootLocationId);

        return $this->response($template, [
            'menuItems' => $menuItems,
            'currentLocationId' => $currentLocationId,
        ]);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function response(string $template, array $data): Response
    {
        $response = new Response();
        $response->setVary('X-User-Hash');
        $response->setContent(
            $this->twig->render(
                $template, $data
            )
        );

        return $response;
    }
}
