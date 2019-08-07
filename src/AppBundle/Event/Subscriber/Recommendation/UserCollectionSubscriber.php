<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Event\Subscriber\Recommendation;

use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use eZ\Publish\API\Repository\UserService;
use EzSystems\EzRecommendationClient\Event\GenerateUserCollectionDataEvent;
use EzSystems\EzRecommendationClient\Value\Output\Attribute;
use EzSystems\EzRecommendationClient\Value\Output\User;
use EzSystems\EzRecommendationClient\Value\Output\UserCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserCollectionSubscriber implements EventSubscriberInterface
{
    /** @var \eZ\Publish\API\Repository\UserService */
    private $userService;

    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var string */
    private $membersUserGroupId;

    /**
     * @param \eZ\Publish\API\Repository\UserService $userService
     * @param \eZ\Publish\API\Repository\Repository $repository
     */
    public function __construct(
        UserService $userService,
        RepositoryInterface $repository,
        string $membersUserGroupId
    ) {
        $this->userService = $userService;
        $this->repository = $repository;
        $this->membersUserGroupId = $membersUserGroupId;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            GenerateUserCollectionDataEvent::NAME => ['onGenerateUserCollectionData', 20],
        ];
    }

    /**
     * @param \EzSystems\EzRecommendationClient\Event\GenerateUserCollectionDataEvent $event
     */
    public function onGenerateUserCollectionData(GenerateUserCollectionDataEvent $event): void
    {
        $users = $this->getUsers();
        $event->setUserCollection(
            $this->generateUserCollection($users)
        );

        $event->setUserCollectionName('demo');
    }

    /**
     * Returns list of users in members group.
     * Loads up to 25 users as an example.
     *
     * @return \eZ\Publish\API\Repository\Values\User\User[]
     */
    private function getUsers(): array
    {
        $membersUserGroupId = $this->membersUserGroupId;

        /** @var \eZ\Publish\API\Repository\Values\User\User[] $users */
        return $this->repository->sudo(
            function (RepositoryInterface $repository) use ($membersUserGroupId) {
                $userGroup = $repository->getUserService()->loadUserGroup($membersUserGroupId);
                return $repository->getUserService()->loadUsersOfUserGroup($userGroup);
            }
        );
    }

    /**
     * Generates UserCollection list with users and their attributes.
     *
     * @param \eZ\Publish\API\Repository\Values\User\User[] $users
     *
     * @return \EzSystems\EzRecommendationClient\Value\Output\UserCollection
     */
    private function generateUserCollection(array $users): UserCollection
    {
        $userCollection = [];

        foreach ($users as $user) {
            $userAttributes = [
                new Attribute('first_name', $user->getFieldValue('first_name')->text),
                new Attribute('last_name', $user->getFieldValue('last_name')->text),
                new Attribute('signature', $user->getFieldValue('signature')->text),
                new Attribute('email', $user->email),
            ];

            $tags = array_map(function ($item) {
                return new Attribute('interests', $item->getKeyword());
            }, $user->getFieldValue('interests')->tags);

            $attributes = array_merge($userAttributes, $tags);

            $userCollection[] = new User('CUSTOMER_' . $user->id, $attributes);
        }

        return new UserCollection($userCollection);
    }
}
