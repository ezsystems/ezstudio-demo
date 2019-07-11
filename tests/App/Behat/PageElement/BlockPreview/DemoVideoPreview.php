<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\BlockPreview;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\VideoPreview;
use PHPUnit\Framework\Assert;

class DemoVideoPreview extends VideoPreview
{
    protected $imageFilename;

    public function __construct(BrowserContext $context)
    {
        parent::__construct($context);
        $this->fields['imgSelector'] = sprintf('%s %s', $this->blockSelector, 'img');
    }

    public function setExpectedData(array $data)
    {
        $this->imageFilename = $data['parameter1'];
    }

    public function verifyVisibility(): void
    {
        Assert::assertEquals($this->imageFilename, $this->context->findElement($this->fields['imgSelector'], self::TIMEOUT, $this->baseElement)->getAttribute('src'));
    }
}
