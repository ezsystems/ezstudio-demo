<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\AppBundle\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\CollectionBlock;

class DemoCollectionBlock extends CollectionBlock
{
    public function setDefaultTestingConfiguration(string $blockName): void
    {
        $this->setInputField('Name', $blockName);
        $this->addContentItems([
            'Home/Places & Tastes/Tastes/Mexican Cuisine',
            'Home/Places & Tastes/Tastes/Ethiopian Cuisine',
            'Home/Places & Tastes/Tastes/Norwegian Cuisine',
            'Home/Places & Tastes/Tastes/Polish Cuisine',
        ]);
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => 'Mexican Cuisine,Ethiopian Cuisine,Norwegian Cuisine,Polish Cuisine', 'parameter2' => 'card'];
    }
}
