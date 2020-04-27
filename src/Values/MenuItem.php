<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Values;

final class MenuItem
{
    /** @var string */
    private $name;

    /** @var int */
    private $locationId;

    /** @var int */
    private $contentId;

    /** @var self[] */
    private $children;

    public function __construct(
        string $name,
        int $locationId,
        int $contentId,
        array $children = []
    ) {
        $this->name = $name;
        $this->locationId = $locationId;
        $this->contentId = $contentId;
        $this->children = $children;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function locationId(): int
    {
        return $this->locationId;
    }

    public function contentId(): int
    {
        return $this->contentId;
    }

    public function children(): array
    {
        return $this->children;
    }
}
