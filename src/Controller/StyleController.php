<?php
/** 
 * The file is for style
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

use App\Entity\Style;
use App\Form\StyleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/** 
 * The class is for style
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class StyleController extends AbstractController
{
    /**
     * Style index
     * 
     * @param object $request 
     * 
     * @Route("/admin/style", name="style")
     * 
     * @return reponse
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
     * Remove one style
     * 
     * @param object $style 
     * 
     * @Route("/admin/style/remove/{id}", name="style-remove", requirements={"id"="\d+"})
     *
     * @return response
     */
    public function remove(Style $style)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($style);
        $entityManager->flush();
        $this->addFlash('success', 'Votre style à étais supprimer');
        return $this->redirectToRoute('style');
    }
    /**
     * Update style
     * 
     * @param int    $id 
     * @param object $style 
     * @param object $request  
     * 
     * @Route("/admin/style/update/{id}", name="style-update", requirements={"id"="\d+"})
     * 
     * @return response
     */
    public function update(int $id, Style $style ,Request $request)
    {
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
