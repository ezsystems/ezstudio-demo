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
use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class ContactFormController extends Controller
{
    /** @var \App\Mail\Sender */
    private $sender;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    public function __construct(
        Sender $sender,
        RouterInterface $router
    ) {
        $this->sender = $sender;
        $this->router = $router;
    }

    /**
     * Displays contact form and sends e-mail message when using POST request.
     *
     * @return \eZ\Publish\Core\MVC\Symfony\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function showContactFormAction(View $view, Request $request)
    {
        $form = $this->createForm(ContactType::class);

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
}
