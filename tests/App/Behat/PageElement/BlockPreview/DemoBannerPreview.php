<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\BlockPreview;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\BannerPreview;
use PHPUnit\Framework\Assert;

class DemoBannerPreview extends BannerPreview
{
    private $imageFile;

    public function __construct(BrowserContext $context)
    {
        parent::__construct($context);
        $this->fields['imageSelector'] = sprintf('%s %s', $this->blockSelector, '.banner-full');
    }

    public function setExpectedData(array $data)
    {
        parent::setExpectedData($data);
        $this->imageFile = $data['parameter2'];
    }

    public function verifyVisibility(): void
    {
        parent::verifyVisibility();
        Assert::assertContains($this->imageFile, $this->context->findElement($this->fields['imageSelector'], self::TIMEOUT, $this->baseElement)->getAttribute('src'));
    }
}
