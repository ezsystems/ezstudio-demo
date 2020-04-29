<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Twig;

use eZ\Publish\API\Repository\ContentService as ContentServiceInterface;
use eZ\Publish\API\Repository\LocationService as LocationServiceInterface;
use eZ\Publish\API\Repository\Values\Content\Location;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AppLocationExtension extends AbstractExtension
{
    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    public function __construct(
        LocationServiceInterface $locationService,
        ContentServiceInterface $contentService
    ) {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('app_location_by_remote_id',  [$this, 'getLocationByRemoteId']),
            new TwigFunction('app_location_by_location_id', [$this, 'getLocationByLocationId']),
        ];
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function getLocationByRemoteId(string $remoteId): Location
    {
        $content = $this->contentService->loadContentByRemoteId($remoteId);
        
        return $this->locationService->loadLocation($content->contentInfo->mainLocationId);
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function getLocationByLocationId(int $locationId): Location
    {
        return $this->locationService->loadLocation($locationId);
    }
}
