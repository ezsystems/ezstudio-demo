<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\Controller;

use App\Event\FormSubmittedEvent;
use App\Event\SendMailEvent;
use App\Form\Type\ContactType;
use App\Model\Contact;
use App\ValueObject\MailMessage;
use Exception;
use eZ\Bundle\EzPublishCoreBundle\Controller;
use eZ\Publish\Core\MVC\Symfony\View\View;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ContactFormController extends Controller
{
    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \Symfony\Contracts\Translation\TranslatorInterface */
    private $translator;

    /** @var string */
    private $sender;

    /** @var array */
    private $recipients;

    public function __construct(
        RouterInterface $router,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator,
        string $sender,
        array $recipients
    ) {
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->sender = $sender;
        $this->recipients = $recipients;
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
                try {
                    $message = $this->createMailMessage($form->getData());

                    $this->eventDispatcher->dispatch(
                        new SendMailEvent($message)
                    );

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
     * @param \App\Model\Contact $contact
     *
     * @return \App\ValueObject\MailMessage
     */
    private function createMailMessage(Contact $contact): MailMessage
    {
        return new MailMessage(
            $this->translator->trans('You have a new message from %from%', ['%from%' => $contact->getFrom()]),
            $this->renderView('/themes/maison/mail/contact.html.twig', [
                'contact' => $contact,
            ]),
            $this->sender,
            $this->recipients
        );
    }
}
