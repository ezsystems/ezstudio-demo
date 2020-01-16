<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageObject;

use EzSystems\Behat\Core\Environment\EnvironmentConstants;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageObject\LandingPagePreview;

class DemoLandingPagePreview extends LandingPagePreview
{
    public function setTitle(string $title): void
    {
        $this->pageTitle = $title;
    }

    public function getDefaultPreviewData(): ?array
    {
        if ($this->pageTitle === EnvironmentConstants::get('MAIN_LANDING_PAGE_HEADER')) {
            return [['blockType', 'parameter1'], ['Hero', EnvironmentConstants::get('MAIN_LANDING_PAGE_HEADER')]];
        }

        return parent::getDefaultPreviewData();
    }

    public function verifyTitle(): void
    {
        // no title for Demo Pages
    }
}
