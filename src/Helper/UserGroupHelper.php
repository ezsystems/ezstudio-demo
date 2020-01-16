<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Helper;

use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use eZ\Publish\API\Repository\Values\User\User as ApiUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class UserGroupHelper
{
    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        RepositoryInterface $repository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Checks if current user's groups exists in one of passed user groups location Ids.
     *
     * @throws \Exception
     */
    public function isCurrentUserInOneOfTheGroups(array $userGroupsLocationIds): bool
    {
        return 0 !== \count(array_intersect($this->getCurrentUserGroupsIds(), $userGroupsLocationIds));
    }

    /**
     * Returns User Groups Location Ids based on current user..
     *
     * @return int[]
     *
     * @throws \Exception
     */
    private function getCurrentUserGroupsIds(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !\is_object($token->getUser())) {
            return [];
        }

        $userGroups = $this->loadUserGroups($token->getUser()->getAPIUser());

        return array_map(function ($userGroup) {
            return $userGroup->contentInfo->mainLocationId;
        }, $userGroups);
    }

    /**
     * Loads User Groups of User, regardless to user limitations.
     *
     * @return \eZ\Publish\API\Repository\Values\User\UserGroup[]
     *
     * @throws \Exception
     */
    private function loadUserGroups(ApiUser $apiUser): array
    {
        return $this->repository->sudo(
            function (RepositoryInterface $repository) use ($apiUser) {
                return $repository->getUserService()->loadUserGroupsOfUser($apiUser);
            }
        );
    }
}
