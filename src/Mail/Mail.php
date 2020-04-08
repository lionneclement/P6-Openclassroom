<?php

namespace App\Mail;

use Twig\Environment;

class Mail
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    public function forgotPassword(string $token, object $user)
    {
        $message = (new \Swift_Message('Lien pour réinitialiser son mot de passe'))
            ->setFrom('admin@snowboard.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render(
                    'mail/forgotPassword.html.twig',
                    ['name' => $user->getName(), 'token' => $token]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
