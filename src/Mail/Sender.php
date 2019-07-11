<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Mail;

use App\Model\Contact;
use Swift_Mailer;
use Swift_Message;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment as Templating;

class Sender
{
    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var \Symfony\Component\Translation\TranslatorInterface|TranslatorInterface */
    protected $translator;

    /** @var \Symfony\Bundle\TwigBundle\TwigEngine */
    protected $templating;

    /** @var string */
    protected $senderEmail;

    /** @var string */
    protected $recipientEmail;

    /**
     * @param \Swift_Mailer $mailer
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Twig\Environment $templating
     * @param string $senderEmail
     * @param string $recipientEmail
     */
    public function __construct(
        Swift_Mailer $mailer,
        TranslatorInterface $translator,
        Templating $templating,
        $senderEmail,
        $recipientEmail
    ) {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->templating = $templating;
        $this->senderEmail = $senderEmail;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * @param \App\Model\Contact $contact
     *
     * @throws \Twig\Error\Error
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function send(Contact $contact): void
    {
        $title = $this->translator->trans('You have a new message from %from%', ['%from%' => $contact->getFrom()]);
        $message = Swift_Message::newInstance($title, $contact->getMessage())
            ->setFrom($this->senderEmail)
            ->setTo($this->recipientEmail)
            ->setReplyTo($this->recipientEmail)
            ->setBody(
                $this->templating->render('/themes/tastefulplanet/mail/contact.html.twig', [
                    'contact' => $contact
                ])
            );

        $this->mailer->send($message);
    }
}
