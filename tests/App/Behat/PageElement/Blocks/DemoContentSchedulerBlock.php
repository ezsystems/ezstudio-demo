<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\AppBundle\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\ContentSchedulerBlock;

class DemoContentSchedulerBlock extends ContentSchedulerBlock
{
    public function setDefaultTestingConfiguration(string $blockName): void
    {
        $this->setInputField('Name', $blockName);
        $this->addContentItems([
            'Home/Blog/Where we get our best writing done',
            'Home/Blog/Why we love NYC',
        ]);
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => 'Why we love NYC,Where we get our best writing done', 'parameter2' => 'card'];
    }
}
