<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\AppBundle\Behat\PageElement\Blocks;

use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\Blocks\CodeBlock;

class DemoCodeBlock extends CodeBlock
{
    public function setDefaultTestingConfiguration(string $blockName): void
    {
        $this->setInputField('Name', $blockName);
        $this->setInputField('Content', '<b>Confusionwillbemyepitaph</b>');
        $this->submitForm();
    }

    public function getDefaultPreviewData(): array
    {
        return ['parameter1' => '<b>Confusionwillbemyepitaph</b>'];
    }
}
