<?php

/** 
 * The file is for user
 * 
 * PHP version 7.3.5
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */

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
use App\Form\ProfileType;
use App\Form\ResetPasswordType;
use App\Mail\Mail;
use App\Tools\File;
use App\Tools\Token;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/** 
 * The class is for user
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class UserController extends AbstractController
{
    /**
     * Login
     * 
     * @param object $authenticationUtils 
     * 
     * @Route("/anony/login", name="app_login")
     * 
     * @return response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout
     * 
     * @Route("/auth/logout", name="app_logout")
     * 
     * @return response
     */
    public function logout()
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion sur votre pare-feu.');
    }
    /**
     * Register
     * 
     * @param object $request 
     * @param object $passwordEncoder 
     * @param object $guardHandler 
     * @param object $authenticator 
     * 
     * @Route("/anony/register", name="app_register")
     * 
     * @return response
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
            'security/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }
    /**
     * Forgot password
     * 
     * @param objet $request 
     * @param objet $session 
     * @param objet $mail 
     * @param objet $token 
     * 
     * @Route("/anony/password/forgot", name="forgot_password")
     * 
     * @return response
     */
    public function forgotPassword(Request $request, SessionInterface $session, Mail $mail, Token $token): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();

            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $email]);
            if (!$user) {
                $this->addFlash('error', 'Le compte n\'existe pas.');
            } else {
                $tokenGenerate = $token->generator(10);
                $session->set('token', $tokenGenerate);
                $session->set('email', $email);
                $mail->forgotPassword($tokenGenerate, $user);

                $this->addFlash('success', 'Vous allez recevoir un email');
            }
        }
        return $this->render(
            'security/forgot.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Reset password
     * 
     * @param object $request 
     * @param object $session 
     * @param object $passwordEncoder 
     * 
     * @Route("/anony/password/reset", name="reset_password")
     * 
     * @return response
     */
    public function resetPassword(Request $request, SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $token = $request->query->get('token');
        if ($session->get('token') == $token) {
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

                $this->addFlash('success', 'Votre mot de passe a été modifié');
                $session->clear();
                return $this->redirectToRoute('app_login');
            }
            return $this->render(
                'security/reset.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
        return $this->redirectToRoute('home_page');
    }
    /**
     * Render profile
     * 
     * @param object $user 
     * @param object $request 
     * @param object $file 
     * 
     * @Route("/auth/profile", name="profile")
     * 
     * @return response
     */
    public function userProfile(UserInterface $user, Request $request, File $file): Response
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre profile à été modifié');
            $image = $form['imageName']->getData();
            if ($image) {
                $file->updateProfileImage($image, $user);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }
        return $this->render(
            'profile/index.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
