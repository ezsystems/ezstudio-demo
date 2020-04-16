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

/**
 * Twig helper for fetching Content
 */
final class AppContentExtension extends AbstractExtension
{
    /** var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    public function __construct(
        ContentServiceInterface $contentService
    ) {
        $this->contentService = $contentService;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string the extension name
     */
    public function getName(): string
    {
        return 'app.content';
    }

    /**
     * Returns a list of functions to add to the existing list.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_content_by_content_id', [$this, 'contentByContentId']),
        ];
    }

    /**
     * @param int $contentId
     *
     * @return \eZ\Publish\API\Repository\Values\Content\Content
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function contentByContentId(int $contentId): Content
    {
        return $this->contentService->loadContent($contentId);
    }
}
