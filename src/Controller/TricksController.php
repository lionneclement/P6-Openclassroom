<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Tricks;
use App\Form\PhotosType;
use App\Form\TricksType;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/show/{id}", name="show_tricks", requirements={"id"="\d+"})
     */
    public function showTricks(Request $request, int $id, MediaController $mediaController, File $File)
    {
        $trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->find($id);
        
        $photos = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->findByTricksId($id);

        $form = $this->createForm(PhotosType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form['Photos']->getData();

            if($imageFiles){
                $this->addFlash('success', 'Votre photo à étais enregistrer');
                $mediaController->addPhotos($imageFiles, $trick, $File);
                return $this->redirect($request->getUri());
            }
        }
        return $this->render('tricks/show.html.twig', [
            'photos' => $photos,
            'trick'=> $trick,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/tricks/create", name="create_tricks")
     */
    public function createTricks(Request $request)
    {
        $form = $this->createForm(TricksType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Votre Tricks à étais enregistrer');
            $formData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formData);
            $entityManager->flush();
        }
        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks/update/{id}", name="update_tricks", requirements={"id"="\d+"})
     */
    public function updateTricks(Request $request,int $id)
    {
        $trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->find($id);

        if (!$trick) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        $form = $this->createForm(TricksType::class, $trick);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Votre Tricks à étais modifier');
            $formData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formData);
            $entityManager->flush();
        }
        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/tricks/delete/{id}", name="remove_tricks", requirements={"id"="\d+"})
     */
    public function removeTricks($id, MediaController $mediaController, File $File)
    {
        $Trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->find($id);
        
        $mediaController->removeMultiplePhotos($id, $File);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($Trick);
        $em->flush();

        return $this->redirect('/');
    }
}
