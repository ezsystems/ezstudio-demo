<?php

use App\Kernel;
use eZ\Publish\API\Repository\Repository;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__) . '/config/bootstrap.php';

// Environment is taken from "APP_ENV" variable, if not set, defaults to "prod"
$environment = getenv('APP_ENV');
if ($environment === false) {
    $environment = 'prod';
}

// Depending on the SYMFONY_DEBUG environment variable, tells whether Symfony should be loaded with debugging.
// If not set, or "", it is auto activated if in "dev" environment.
if (($useDebugging = getenv('APP_DEBUG')) === false || $useDebugging === '') {
    $useDebugging = $environment === 'dev';
}

if ($useDebugging) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts([$trustedHosts]);
}

$kernel = new Kernel($environment, $useDebugging);
$kernel->boot();

$container = $kernel->getContainer();
$repository = $container->get('ezpublish.api.repository');

$roleId = 1;

/** @var \eZ\Publish\Core\Repository\Values\User\Role $role */
$repository->sudo(
    function (Repository $repository) use ($roleId) {
        /* @var $repository \eZ\Publish\Core\Repository\Repository */
        $role = $repository->getRoleService()->loadRole($roleId);

        $policy = [
            'module' => 'content',
            'function' => 'read',
            'limitations' => [
                [
                    'identifier' => 'Subtree',
                    'values' => ['/1/43/51/', '/1/43/53/', '/1/59/'],
                ],
            ],
        ];

        $roleService = $repository->getRoleService();
        $roleDraft = $roleService->createRoleDraft($role);
        $policyCreateStruct = $roleService->newPolicyCreateStruct($policy['module'], $policy['function']);

        if (array_key_exists('limitations', $policy)) {
            foreach ($policy['limitations'] as $limitation) {
                $limitationType = $roleService->getLimitationType($limitation['identifier']);
                $limitationObject = $limitationType->buildValue($limitation['values']);
                $policyCreateStruct->addLimitation($limitationObject);
            }
        }

        $updatedRoleDraft = $roleService->addPolicyByRoleDraft($roleDraft, $policyCreateStruct);
        $roleService->publishRoleDraft($updatedRoleDraft);

        echo  'OK' . PHP_EOL;
    }
);
