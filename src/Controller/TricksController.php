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
     * @Route("/tricks", name="create_tricks")
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
            $task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
        }
        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'form' => $form->createView(),
        ]);
    }
}
