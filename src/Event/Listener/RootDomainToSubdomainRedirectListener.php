<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event\Listener;

use eZ\Publish\Core\MVC\Symfony\SiteAccess\SiteAccessServiceInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Only for demo purposes - depends on configuration specific for the HostElement SiteAccess matcher.
 * Requesting root domain will redirect to subdomain with default siteAccess.
 */
final class RootDomainToSubdomainRedirectListener
{
    private const DEFAULT_MATCHING_TYPE = 'default';

    /** @var \eZ\Publish\Core\MVC\Symfony\SiteAccess\SiteAccessServiceInterface */
    private $siteAccessService;

    /** @var string */
    private $rootDomainSiteAccess;

    public function __construct(
        SiteAccessServiceInterface $siteAccessService,
        string $rootDomainSiteAccess
    ) {
        $this->siteAccessService = $siteAccessService;
        $this->rootDomainSiteAccess = $rootDomainSiteAccess;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $currentSiteAccess = $this->siteAccessService->getCurrent();

        if (null === $currentSiteAccess
            || $currentSiteAccess->name !== $this->rootDomainSiteAccess
            || $currentSiteAccess->matchingType !== self::DEFAULT_MATCHING_TYPE
        ) {
            return;
        }

        $request = $event->getRequest();
        $currentHost = $request->getHost();
        $currentUri = $request->getUri();

        if (strpos($currentHost, $this->rootDomainSiteAccess.'.') === 0
            || filter_var($currentHost, FILTER_VALIDATE_IP) !== false
            || $currentHost === 'localhost'
        ) {
            return;
        }

        $fixedHost = $this->rootDomainSiteAccess.'.'.$currentHost;
        $redirectUri = preg_replace("/{$currentHost}/", $fixedHost, $currentUri, 1);

        $event->setResponse(new RedirectResponse($redirectUri));
        $event->stopPropagation();
    }
}
