<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\ContentListBlock;

class DemoContentListBlock extends ContentListBlock
{
    public function setDefaultTestingConfiguration(string $blockName): void
    {
        $this->setInputField('Name', $blockName);
        $this->addContent('Home/Places & Tastes/Tastes');
        $this->setInputField('Limit', '5');
        $this->selectContentType('Article');
        $this->switchTab('Design');
        $this->setLayout('cards');
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => 'ContentListBlock', 'parameter2' => 'Mexican Cuisine,Ethiopian Cuisine,Thai Cuisine,Israeli Cuisine,Jamaican Cuisine'];
    }
}
