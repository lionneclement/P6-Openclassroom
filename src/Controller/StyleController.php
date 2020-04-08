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
            $this->addFlash('success', 'Votre style à étais enregistrer');
            return $this->redirect($request->getUri());
        }
        return $this->render(
            'style/index.html.twig', [
            'styles' => $styles,
            'form' => $form->createView(),
            ]
        );
    }
    /**
     * @Route("/style/remove/{id}", name="style-remove", requirements={"id"="\d+"})
     */
    public function remove(int $id)
    {
        $style = $this->getDoctrine()
            ->getRepository(Style::class)
            ->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($style);
        $entityManager->flush();
        $this->addFlash('success', 'Votre style à étais supprimer');
        return $this->redirectToRoute('style');
    }
    /**
     * @Route("/style/update/{id}", name="style-update", requirements={"id"="\d+"})
     */
    public function update(int $id,Request $request)
    {
        $style = $this->getDoctrine()
            ->getRepository(Style::class)
            ->find($id);

        $form = $this->createForm(StyleType::class, $style);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($style);
            $entityManager->flush();
            $this->addFlash('success', 'Votre style à étais modifier');
            return $this->redirectToRoute('style', ['_fragment' => $id]);
        }
        return $this->render(
            'style/update.html.twig', [
            'form' => $form->createView(),
            ]
        );
    }
}
