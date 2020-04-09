<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Twig;

use eZ\Publish\API\Repository\ContentService as ContentServiceInterface;
use eZ\Publish\API\Repository\LocationService as LocationServiceInterface;
use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use eZ\Publish\API\Repository\Values\Content\Location;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig helper for fetching ContentInfo Based on Location Id.
 */
final class ContentInfoByLocationIdExtension extends AbstractExtension
{
    /** var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    public function __construct(
        LocationServiceInterface $locationService,
        ContentServiceInterface $contentService
    ) {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string the extension name
     */
    public function getName(): string
    {
        return 'app.content_info';
    }

    /**
     * Returns a list of functions to add to the existing list.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_content_info_by_location_id', [$this, 'contentInfoByLocationId']),
            new TwigFunction('app_content_info_by_content_id', [$this, 'contentInfoByContentId']),
            new TwigFunction('app_content_by_content_id', [$this, 'contentByContentId']),
            new TwigFunction('app_location_by_location_id', [$this, 'locationByLocationId']),
        ];
    }

    /**
     * Return ContentInfo based on Location Id.
     *
     * @param $locationId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function contentInfoByLocationId($locationId): ContentInfo
    {
        return $this->locationService->loadLocation($locationId)->getContentInfo();
    }

    /**
     * Return ContentInfo based on Content Id.
     *
     * @param $contentId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function contentInfoByContentId($contentId): ContentInfo
    {
        return $this->contentService->loadContent($contentId)->getVersionInfo()->getContentInfo();
    }



    public function contentByContentId(int $contentId): Content
    {
        return $this->contentService->loadContent($contentId);
    }
}
