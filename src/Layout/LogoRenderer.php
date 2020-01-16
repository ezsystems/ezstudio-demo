<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Layout;

use App\Helper\ContentHelper;
use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use Twig\Extension\RuntimeExtensionInterface;

final class LogoRenderer extends AbstractRenderer implements LayoutRendererInterface, RuntimeExtensionInterface
{
    public const LOGO_FIELD_NAME = 'logo';

    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    public function __construct(
        ContentHelper $contentHelper,
        RepositoryInterface $repository
    ) {
        parent::__construct($contentHelper);

        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function render(?int $contentId = null): string
    {
        $imageContentId = (int)$this->getContentHelper()->getContentFieldValue($contentId, self::LOGO_FIELD_NAME);

        if (!$imageContentId) {
            return '';
        }

        return $this->repository->sudo(
            function () use ($imageContentId) {
                $imageContent = $this->getContentHelper()->loadContent($imageContentId);

                /** @var \eZ\Publish\Core\FieldType\Image\Value $imageField */
                $imageField = $imageContent->getFieldValue('image');

                return $imageField->uri;
            }
        );
    }
}
