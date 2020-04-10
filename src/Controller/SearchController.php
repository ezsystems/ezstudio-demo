<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Form\Data\SearchData;
use App\Form\Type\SearchType;
use App\QueryType\SearchQueryType;
use App\Search\PagerSearchContentMapper;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\API\Repository\SearchService as SearchServiceInterface;
use eZ\Publish\Core\Pagination\Pagerfanta\ContentSearchHitAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
    /** @var \eZ\Publish\API\Repository\SearchService */
    private $searchService;

    /** @var \App\QueryType\SearchQueryType */
    private $queryType;

    /** @var \App\Search\PagerSearchContentMapper */
    private $contentMapper;

    public function __construct(
        SearchServiceInterface $searchService,
        SearchQueryType $queryType,
        PagerSearchContentMapper $contentMapper
    ) {
        $this->queryType = $queryType;
        $this->searchService = $searchService;
        $this->contentMapper = $contentMapper;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormAction(Request $request): Response
    {
        $form = $this->getForm(new SearchData());

        return $this->render('@ezdesign/search/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request): Response
    {
        $form = $this->getForm();
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new Response();
        }

        /** @var \App\Form\Data\SearchData $data */
        $data = $form->getData();

        $pagerfanta = new Pagerfanta(
            new ContentSearchHitAdapter(
                $this->queryType->getQuery(['search_data' => $data]),
                $this->searchService
            )
        );

        $pagerfanta->setMaxPerPage($data->getLimit());
        $pagerfanta->setCurrentPage(min($data->getPage(), $pagerfanta->getNbPages()));

        return $this->render('@ezdesign/search/index.html.twig', [
            'search_value' => $data->getQuery(),
            'results' => $this->contentMapper->map($pagerfanta),
            'pagerfanta' => $pagerfanta
        ]);
    }

    /**
     * @param \App\Form\Data\SearchData|null $searchData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function getForm(?SearchData $searchData = null): FormInterface
    {
        return
            $this->createForm(SearchType::class, $searchData, [
                'action' => $this->generateUrl('app.search'),
                'method' => Request::METHOD_GET,
            ]);
    }
}
