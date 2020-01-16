<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\App\Behat\PageObject;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\Behat\Browser\Page\Page;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageObject\EnterprisePageObjectFactory;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageObject\LandingPagePreview;

class DemoEnterprisePageObjectFactory extends EnterprisePageObjectFactory
{
    /**
     * @param null[]|string[] ...$parameters
     */
    public static function createPage(BrowserContext $context, string $pageName, ?string ...$parameters): Page
    {
        switch ($pageName) {
            case LandingPagePreview::PAGE_NAME:
                return new DemoLandingPagePreview($context);
            default:
                return parent::createPage($context, $pageName, ...$parameters);
        }
    }
}
