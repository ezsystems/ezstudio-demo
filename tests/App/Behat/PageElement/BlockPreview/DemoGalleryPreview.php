<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\BlockPreview;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\GalleryPreview;
use PHPUnit\Framework\Assert;

class DemoGalleryPreview extends GalleryPreview
{
    private $expectedItemCount;

    public function __construct(BrowserContext $context)
    {
        parent::__construct($context);
        $this->fields['galleryItemSelector'] = sprintf('%s %s', $this->blockSelector, '.carousel-item');
    }

    public function setExpectedData(array $data)
    {
        parent::setExpectedData($data);
        $this->expectedItemCount = (int)$data['parameter2'];
    }

    public function verifyVisibility(): void
    {
        parent::verifyVisibility();
        Assert::assertCount($this->expectedItemCount, $this->context->findAllElements($this->fields['galleryItemSelector']));
    }
}
