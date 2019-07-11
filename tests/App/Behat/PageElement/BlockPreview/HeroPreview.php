<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\BlockPreview;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\BlockPreview;
use PHPUnit\Framework\Assert;

class HeroPreview extends BlockPreview
{
    public const ELEMENT_NAME = 'Hero Preview';

    private $expectedTitle;

    public function __construct(BrowserContext $context)
    {
        parent::__construct($context);
        $this->blockSelector = '.block-hero';
        $this->fields['headerSelector'] = sprintf('%s %s', $this->blockSelector, 'h1.field-title');
    }

    public function setExpectedData(array $data)
    {
        $this->expectedTitle = $data['parameter1'];
    }

    public function verifyVisibility(): void
    {
        Assert::assertSame($this->expectedTitle, $this->context->findElement($this->fields['headerSelector'], self::TIMEOUT, $this->baseElement)->getText());
    }
}
