<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Form\Data;

use Symfony\Component\Validator\Constraints as Assert;

class SearchData
{
    /** @var int */
    private $page;

    /**
     * @var int
     *
     * @Assert\Range(
     *     max = 100
     * )
     */
    private $limit;

    /** @var string */
    private $query;

    public function __construct(
        int $page = 1,
        int $limit = 10,
        ?string $query = null
    ) {
        $this->page = $page;
        $this->limit = $limit;
        $this->query = $query;
    }

    /**
     * @param int $limit
     *
     * @return \App\Form\Data\SearchData
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $page
     *
     * @return \App\Form\Data\SearchData
     */
    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param string|null $query
     *
     * @return \App\Form\Data\SearchData
     */
    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }
}
