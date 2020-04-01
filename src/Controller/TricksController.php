<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TricksType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
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
}
