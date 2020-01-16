<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Event\Subscriber;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Events\Content\PublishVersionEvent;
use eZ\Publish\API\Repository\PermissionResolver;
use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use EzSystems\EzPlatformWorkflow\Exception\NotFoundException;
use EzSystems\EzPlatformWorkflow\Registry\WorkflowDefinitionMetadataRegistry;
use EzSystems\EzPlatformWorkflow\Registry\WorkflowRegistryInterface;
use EzSystems\EzPlatformWorkflow\Service\WorkflowServiceInterface;
use EzSystems\EzPlatformWorkflow\Value\WorkflowMetadata;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Transition;

final class EndWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var \EzSystems\EzPlatformWorkflow\Service\WorkflowServiceInterface */
    private $workflowService;

    /** @var \EzSystems\EzPlatformWorkflow\Registry\WorkflowRegistryInterface */
    private $workflowRegistry;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \EzSystems\EzPlatformWorkflow\Registry\WorkflowDefinitionMetadataRegistry */
    private $workflowMetadataRegistry;

    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    /**
     * @param \EzSystems\EzPlatformWorkflow\Service\WorkflowServiceInterface $workflowService
     * @param \EzSystems\EzPlatformWorkflow\Registry\WorkflowRegistryInterface $workflowRegistry
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \EzSystems\EzPlatformWorkflow\Registry\WorkflowDefinitionMetadataRegistry $workflowMetadataRegistry
     * @param \eZ\Publish\API\Repository\Repository $repository
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     */
    public function __construct(
        WorkflowServiceInterface $workflowService,
        WorkflowRegistryInterface $workflowRegistry,
        ContentService $contentService,
        WorkflowDefinitionMetadataRegistry $workflowMetadataRegistry,
        RepositoryInterface $repository,
        PermissionResolver $permissionResolver
    ) {
        $this->workflowService = $workflowService;
        $this->workflowRegistry = $workflowRegistry;
        $this->contentService = $contentService;
        $this->workflowMetadataRegistry = $workflowMetadataRegistry;
        $this->repository = $repository;
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PublishVersionEvent::class => ['onPublishVersion', -50],
        ];
    }

    /**
     * Automatically starts supported workflows after publishing content.
     *
     * @param \eZ\Publish\API\Repository\Events\Content\PublishVersionEvent $event
     */
    public function onPublishVersion(PublishVersionEvent $event): void
    {
        $content = $event->getContent();
        $supportedWorkflows = $this->workflowRegistry->getSupportedWorkflows($content);

        foreach ($supportedWorkflows as $workflow) {
            try {
                $workflowMetadata = $this->workflowService->loadWorkflowMetadataForContent(
                    $content,
                    $workflow->getName()
                );
            } catch (NotFoundException $e) {
                continue;
            }

            $workflowCurrentState = !empty($workflowMetadata->markings) ? end($workflowMetadata->markings)->name : '';
            $transitions = $workflowMetadata->workflow->getDefinition()->getTransitions();
            $lastStage = $this->getLastStage($transitions, $workflow->getName());

            if (!$lastStage) {
                continue;
            }

            $transitionsToMake = $this->findPathToInitialStage($transitions, $workflowCurrentState, $lastStage);
            $this->applyTransitions($transitionsToMake, $workflowMetadata);
        }
    }

    private function applyTransitions(array $transitionsToMake, WorkflowMetadata $workflowMetadata): void
    {
        foreach ($transitionsToMake as $transitionToMake) {
            $this->permissionResolver->sudo(
                function () use ($workflowMetadata, $transitionToMake) {
                    if ($this->workflowService->can($workflowMetadata, $transitionToMake)) {
                        $this->workflowService->apply($workflowMetadata, $transitionToMake, '');

                    }
                },
                $this->repository
            );
        }
    }

    private function getLastStage(array $transitions, string $workflowName): ?Transition
    {
        $workflowDefinitionMetadata = $this->workflowMetadataRegistry->getWorkflowMetadata($workflowName);

        /** @var Transition $transition */
        foreach ($transitions as $transition) {
            foreach ($transition->getTos() as $to) {
                if ($workflowDefinitionMetadata->getStageMetadata($to)->isLastStage()) {
                    return $transition;
                }
            }
        }

        return null;
    }

    private function findPathToInitialStage(array $transitions, string $workflowCurrentState, Transition $transition)
    {
        $path = [$transition->getName()];

        while (true) {
            $matched = $this->getMatchedTransitions($transitions, $transition);

            if (empty($matched)) {
                break;
            }

            if (in_array($matched[0]->getName(), $path, true)) {
                break;
            }

            $path[] = $matched[0]->getName();

            if (in_array($workflowCurrentState, $matched[0]->getFroms(), true)) {
                break;
            }

            $transition = $matched[0];
        }

        return array_reverse($path);
    }

    /**
     * @param array $transitions
     * @param Transition $baseTransition
     * @return Transition[]
     */
    private function getMatchedTransitions(array $transitions, Transition $baseTransition): array
    {
        $return = [];

        foreach ($baseTransition->getFroms() as $from) {
            /** @var Transition $transition */
            foreach($transitions as $transition) {
                foreach ($transition->getTos() as $to) {
                    if ($from === $to) {
                        $return[] = $transition;
                    }
                }
            }
        }

        return $return;
    }
}
