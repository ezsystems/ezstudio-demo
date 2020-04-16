<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Layout;

use App\Helper\ContentHelper;

abstract class AbstractRenderer
{
    /** @var \App\Helper\ContentHelper */
    private $contentHelper;

    public function __construct(ContentHelper $contentHelper)
    {
        $this->contentHelper = $contentHelper;
    }

    /**
     * @return \App\Helper\ContentHelper
     */
    protected function getContentHelper(): ContentHelper
    {
        return $this->contentHelper;
    }
}
