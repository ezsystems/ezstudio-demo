<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\ContactType;
use App\Mail\Sender;
use Exception;
use eZ\Publish\Core\MVC\Symfony\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class ContactFormController
{
    /** @var \Symfony\Component\Form\FormFactoryInterface */
    private $formFactory;

    /** @var \App\Mail\Sender */
    private $sender;

    /** @var \Twig\Environment */
    private $twigEnvironment;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    public function __construct(
        FormFactoryInterface $formFactory,
        Sender $sender,
        Environment $twigEnvironment,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->sender = $sender;
        $this->twigEnvironment = $twigEnvironment;
        $this->router = $router;
    }

    /**
     * Displays contact form and sends e-mail message when using POST request.
     *
     * @return \eZ\Publish\Core\MVC\Symfony\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function showContactFormAction(View $view, Request $request)
    {
        $form = $this->formFactory->create(ContactType::class);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $contact = $form->getData();

                try {
                    $this->sender->send($contact);

                    // redirects user to confirmation page after successful sending of e-mail
                    return new RedirectResponse(
                        $this->router->generate('app.submitted')
                    );
                } catch (Exception $e) {
                    //Todo add flash message to notify the user
                }
            }
        }

        $view->addParameters([
            'form' => $form->createView(),
        ]);

        return $view;
    }

    /**
     * Displays confirmation page after successful contact form submission.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function submittedAction(string $template): Response
    {
        return new Response($this->twigEnvironment->render($template));
    }
}
