<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(UserInterface $User, Request $request, File $File)
    {
        $form = $this->createForm(ProfileType::class, $User);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Votre profile à étais modifier');
            $profile = $form->getData();
            
            $image =$form['ImageName']->getData();
            if($image){
                if  ($User->getImageName() != 'default-user.png'){
                    $File->removeImage($User->getImageName());
                }
                $imageFileName = $File->uploadImage($image);
                $profile->setImageName($imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $User,
        ]);
    }
}
