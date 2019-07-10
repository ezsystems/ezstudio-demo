<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\AppBundle\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\EmbedBlock;

class DemoEmbedBlock extends EmbedBlock
{
    public function setDefaultTestingConfiguration(string $blockName): void
    {
        $this->setInputField('Name', $blockName);
        $this->addContent('Home/Products/Tomato soup');
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => 'Tomato soup'];
    }
}
