<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Service\Cache;

use Psr\Cache\CacheItemInterface;

/**
 * Interface CacheServiceInterface.
 */
interface CacheServiceInterface
{
    public function getItem(string $key): CacheItemInterface;

    /**
     * @return array|\Traversable
     */
    public function getItems(array $keys = []): iterable;

    public function saveCacheItems(iterable $cacheItems): void;

    public function save(CacheItemInterface $item): bool;

    public function invalidateTags(array $tags): bool;

    public function getCacheExpirationTime(): int;
}
