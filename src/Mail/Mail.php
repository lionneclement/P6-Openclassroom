<?php
/** 
 * The file is for token
 * 
 * PHP version 7.3.5
 * 
 * @category Mail
 * @package  Mail
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
namespace App\Mail;

use Twig\Environment;
/** 
 * The class is for token
 * 
 * @category Mail
 * @package  Mail
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class Mail
{
    private $_mailer;
    /**
     * Construct
     * 
     * @param object $mailer 
     * @param object $twig 
     */
    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->_mailer = $mailer;
        $this->twig = $twig;
    }
    /**
     * Forgot password
     * 
     * @param string $token 
     * @param object $user 
     * 
     * @return void 
     */
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
        $this->_mailer->send($message);
    }
}
