<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace AppBundle\Event\Subscriber;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\Core\MVC\Symfony\Event\SignalEvent;
use eZ\Publish\Core\MVC\Symfony\MVCEvents;
use eZ\Publish\Core\SignalSlot\Signal\ContentService\PublishVersionSignal;
use EzSystems\EzPlatformWorkflow\Exception\NotFoundException;
use EzSystems\EzPlatformWorkflow\Registry\WorkflowDefinitionMetadataRegistry;
use EzSystems\EzPlatformWorkflow\Registry\WorkflowRegistryInterface;
use EzSystems\EzPlatformWorkflow\Service\WorkflowServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Transition;

class EndWorkflowSubscriber implements EventSubscriberInterface
{
    /** @var \EzSystems\EzPlatformWorkflow\Service\WorkflowServiceInterface */
    private $workflowService;

    /** @var \EzSystems\EzPlatformWorkflow\Registry\WorkflowRegistryInterface */
    private $workflowRegistry;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /** @var \EzSystems\EzPlatformWorkflow\Registry\WorkflowDefinitionMetadataRegistry */
    private $workflowMetadataRegistry;

    /**
     * @param \EzSystems\EzPlatformWorkflow\Service\WorkflowServiceInterface $workflowService
     * @param \EzSystems\EzPlatformWorkflow\Registry\WorkflowRegistryInterface $workflowRegistry
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \EzSystems\EzPlatformWorkflow\Registry\WorkflowDefinitionMetadataRegistry $workflowMetadataRegistry
     */
    public function __construct(
        WorkflowServiceInterface $workflowService,
        WorkflowRegistryInterface $workflowRegistry,
        ContentService $contentService,
        WorkflowDefinitionMetadataRegistry $workflowMetadataRegistry
    ) {
        $this->workflowService = $workflowService;
        $this->workflowRegistry = $workflowRegistry;
        $this->contentService = $contentService;
        $this->workflowMetadataRegistry = $workflowMetadataRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MVCEvents::API_SIGNAL => [
                ['onPublishVersionSignal', 0],
            ],
        ];
    }

    /**
     * Automatically starts supported workflows after updating content.
     *
     * @param \eZ\Publish\Core\MVC\Symfony\Event\SignalEvent $event
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function onPublishVersionSignal(SignalEvent $event): void
    {
        $signal = $event->getSignal();

        if (!$signal instanceof PublishVersionSignal) {
            return;
        }

        $this->doEndWorkflows((int)$signal->contentId, (int)$signal->versionNo);
    }

    /**
     * @param int $contentId
     * @param int $versionNo
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    private function doEndWorkflows(int $contentId, int $versionNo): void
    {
        $content = $this->contentService->loadContent($contentId, [], $versionNo);
        $supportedWorkflows = $this->workflowRegistry->getSupportedWorkflows($content);

        foreach ($supportedWorkflows as $workflow) {
            try {
                $workflowMetadata = $this->workflowService->loadWorkflowMetadataForContent($content, $workflow->getName());

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

            foreach ($transitionsToMake as $transitionToMake) {
                if ($workflow->can($content, $transitionToMake)) {
                    $workflow->apply($content, $transitionToMake);
                }
            }
        }
    }

    public function getLastStage(array $transitions, string $workflowName)
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
