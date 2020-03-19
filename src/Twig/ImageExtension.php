<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Twig;

use eZ\Publish\API\Repository\ContentService as ContentServiceInterface;
use eZ\Publish\API\Repository\Values\Content\Content;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImageExtension extends AbstractExtension
{
    private $contentService;

    public function __construct(ContentServiceInterface $contentService)
    {
        $this->contentService = $contentService;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_image_asset_by_content_id', [$this, 'getImageAssetContentByContentId'])
        ];
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function getImageAssetContentByContentId(int $contentId, string $fieldName): ?Content
    {
        $content = $this->contentService->loadContent($contentId);
        $imageAssetContentId = $content->getField($fieldName)->value;

        if (!$imageAssetContentId->destinationContentId) {
            return null;
        }

        return $this->contentService->loadContent($imageAssetContentId->destinationContentId);
    }
}
