<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Menu;

use App\Exception\MenuStrategyNotFoundException;

final class MenuProviderStrategy
{
    /** @var \App\Menu\MenuProviderInterface[] */
    private $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @throws \App\Exception\MenuStrategyNotFoundException
     */
    public function get(string $type, string $pathString, int $rootLocationId): array
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($type)) {
                return $provider->get($pathString, $rootLocationId);
            }
        }

        throw new MenuStrategyNotFoundException(
            sprintf('MenuStrategy not found for type: %s', $type)
        );
    }
}
