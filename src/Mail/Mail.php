<?php

namespace App\Mail;

class Mail
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    public function sendEmail()
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
               'Hey',
                'text/html'
            )
        ;
    
        $this->mailer->send($message);
    }
}
