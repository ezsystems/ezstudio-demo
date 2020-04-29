<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class AdminRedirectListener
{
    private const ADMIN_DOMAIN = 'admin';

    public function onKernelRequest(RequestEvent $event)
    {
        $host = $event->getRequest()->getHost();
        $requestUri = $event->getRequest()->getUri();
        $requestedUri = $event->getRequest()->getRequestUri();

        $pattern = '/\/' . self::ADMIN_DOMAIN . '(?![.-])/';

        if (1 === preg_match($pattern, $requestedUri)) {
            $hostElements = explode('.', $host);
            $hostElement = current($hostElements);

            $uri = preg_replace($pattern, '', $requestUri, 1);
            $fixedUri = preg_replace("/{$hostElement}/", self::ADMIN_DOMAIN, $uri, 1);

            $event->setResponse(new RedirectResponse($fixedUri));
        }
    }
}
