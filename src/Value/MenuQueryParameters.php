<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Value;

final class MenuQueryParameters
{
    /** @var string */
    private $pathString;

    /** @var int */
    private $depth;

    /** @var string[] */
    private $includedContentTypeIdentifiers;

    /** @var int */
    private $rootLocationId;

    /** @var int */
    private $queryLimit;

    public function __construct(
        string $pathString,
        int $rootLocationId,
        array $includedContentTypeIdentifiers,
        int $depth,
        int $queryLimit = 25) {

        $this->pathString = $pathString;
        $this->rootLocationId = $rootLocationId;
        $this->includedContentTypeIdentifiers = $includedContentTypeIdentifiers;
        $this->depth = $depth;
        $this->queryLimit = $queryLimit;
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

    /**
     * @return int
     */
    public function getQueryLimit(): int
    {
        return $this->queryLimit;
    }
}
