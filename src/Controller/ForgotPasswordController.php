<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Mail\Mail;
use App\Tools\Token;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/password/forgot", name="forgot_password")
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

            if($user){
                $token= $Token->generator(10);
                $session->set('token', $token);
                $session->set('email', $email);
                $Mail->forgotPassword($token, $user);
                
                $this->addFlash('success','Vous aller recevoir un mail');
            }
        }
        return $this->render('password/forgot.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/password/reset", name="reset_password")
     */
    public function resetPassword(Request $request, SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder)
    {
        $token = $request->query->get('token');
        if($session->get('token') == $token){
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
                
                $this->addFlash('success','Votre mot de passe a etais changer');
                $session->clear();
                return $this->redirectToRoute('app_login');
            }
        return $this->render('password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
        }
        return $this->redirectToRoute('home_page');
    }
}
