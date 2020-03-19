<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Value;

final class MenuQueryParameters extends QueryParameters
{
    /** @var string */
    private $pathString;

    /** @var int */
    private $depth;

    /** @var string[] */
    private $includedContentTypeIdentifiers;

    /** @var int */
    private $rootLocationId;

    public function __construct(
        string $pathString,
        int $rootLocationId,
        array $includedContentTypeIdentifiers,
        int $depth,
        int $limit = 25) {

        $this->pathString = $pathString;
        $this->rootLocationId = $rootLocationId;
        $this->includedContentTypeIdentifiers = $includedContentTypeIdentifiers;
        $this->depth = $depth;

        parent::__construct($limit);
    }

    /**
     * @return string
     */
    public function getPathString(): string
    {
        return $this->pathString;
    }

    /**
     * @return int
     */
    public function getRootLocationId(): int
    {
        return $this->rootLocationId;
    }

    /**
     * @return string[]
     */
    public function getIncludedContentTypeIdentifiers(): array
    {
        return $this->includedContentTypeIdentifiers;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }
}
