<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Menu\MenuGenerator;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractMenuController extends Controller
{
    /** @var \App\Menu\MenuGenerator */
    private $menuGenerator;

    public function __construct(MenuGenerator $menuGenerator)
    {
        $this->menuGenerator = $menuGenerator;
    }

    protected function getMenuGenerator(): MenuGenerator
    {
        return $this->menuGenerator;
    }

    /**
     * @throws \Twig\Error\Error
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public abstract function getMenuAction(
        string $template,
        string $pathString,
        int $rootLocationId,
        ?int $currentLocationId
    ): Response;
}
