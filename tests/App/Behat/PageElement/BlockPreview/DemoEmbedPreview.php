<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Tests\AppBundle\Behat\PageElement\BlockPreview;

use EzSystems\Behat\Browser\Context\BrowserContext;
use EzSystems\EzPlatformPageBuilder\Tests\Behat\PageElement\BlockPreview\EmbedPreview;

class DemoEmbedPreview extends EmbedPreview
{
    public function __construct(BrowserContext $context)
    {
        parent::__construct($context);
        $this->fields['headerSelector'] = sprintf('%s %s', $this->blockSelector, 'article.embed h5');
    }
}
