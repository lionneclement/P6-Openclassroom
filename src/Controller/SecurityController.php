<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Mail\Mail;
use App\Tools\Token;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/anony/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username'=>$lastUsername,'error'=>$error]);
    }

    /**
     * @Route("/auth/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/anony/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setImageName('default-user.png');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render(
            'registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            ]
        );
    }
    /**
     * @Route("/anony/password/forgot", name="forgot_password")
     */
    public function forgotPassword(Request $request, SessionInterface $session, Mail $Mail, Token $Token)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $email]);

            if($user) {
                $token= $Token->generator(10);
                $session->set('token', $token);
                $session->set('email', $email);
                $Mail->forgotPassword($token, $user);
                
                $this->addFlash('success', 'Vous aller recevoir un mail');
            }
        }
        return $this->render(
            'password/forgot.html.twig', [
            'form' => $form->createView(),
            ]
        );
    }
    
    /**
     * @Route("/anony/password/reset", name="reset_password")
     */
    public function resetPassword(Request $request, SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder)
    {
        $token = $request->query->get('token');
        if($session->get('token') == $token) {
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $session->get('email')]);

            $form = $this->createForm(ResetPasswordType::class, $user);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $form['password']->getData();
                $user->setPassword($passwordEncoder->encodePassword($user, $password));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre mot de passe a etais changer');
                $session->clear();
                return $this->redirectToRoute('app_login');
            }
            return $this->render(
                'password/reset.html.twig', [
                'form' => $form->createView(),
                ]
            );
        }
        return $this->redirectToRoute('home_page');
    }
}
