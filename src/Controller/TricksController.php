<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Tricks;
use App\Form\PhotoType;
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
    public function showTricks(Request $request, int $id, File $File)
    {
        $trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->find($id);
        
        $photos = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->findByTricksId($id);

        $photo = new Photo;
        $photo->setTricksId($trick);

        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form['Name']->getData();
            if($imageFile){
                $imageFileName = $File->uploadImage($imageFile);
                $photo->setName($imageFileName);
            }
            $this->addFlash('success', 'Votre photo à étais enregistrer');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($photo);
            $entityManager->flush();
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
        $tricks = new Tricks;
        $form = $this->createForm(TricksType::class, $tricks);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                'Votre Tricks à étais enregistrer'
            );
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
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        $form = $this->createForm(TricksType::class, $trick);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                'Votre Tricks à étais modifier'
            );
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
     * @Route("/tricks/photo/delete/{photoId}/{trickId}", name="remove_photo", requirements={"photoId"="\d+","trickId"="\d+" })
     */
    public function removePhoto($photoId, $trickId, File $File)
    {
        $photo = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->find($photoId);

        $filename = $photo->getName();
        $File->removeImage($filename);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirect('/tricks/show/'.$trickId);
    }
}
