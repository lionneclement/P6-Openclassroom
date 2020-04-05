<?php

namespace App\Controller;

use App\Entity\Style;
use App\Form\StyleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StyleController extends AbstractController
{
    /**
     * @Route("/style", name="style")
     */
    public function index(Request $request)
    {
        $styles = $this->getDoctrine()
        ->getRepository(Style::class)
        ->findAll();

        $form = $this->createForm(StyleType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($form->getData());
            $entityManager->flush();
            $this->addFlash('success','Votre Tricks à étais enregistrer');
            return $this->redirect($request->getUri());
        }
        return $this->render('style/index.html.twig', [
            'styles' => $styles,
            'form' => $form->createView(),
        ]);
    }
}
