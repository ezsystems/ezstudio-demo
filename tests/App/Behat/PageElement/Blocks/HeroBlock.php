<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\PageEditorBlock;

class HeroBlock extends PageEditorBlock
{
    /** @var string Name by which Element is recognised */
    public const ELEMENT_NAME = 'Hero';

    public function setDefaultTestingConfiguration(string $blockName)
    {
        $this->setInputField('Name', $blockName);
        $this->setInputField('Text', 'TestHeroBlock');
        $this->addContent('Home/eZ Platform');
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => 'TestHeroBlock'];
    }
}
