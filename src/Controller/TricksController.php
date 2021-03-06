<?php

/** 
 * The file is for trick
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

use App\Entity\Comment;
use App\Entity\Photo;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\TricksType;
use App\Tools\File;
use App\Tools\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/** 
 * The class is for trick
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class TricksController extends AbstractController
{
    /**
     * Show tricks
     * 
     * @param object $request 
     * @param object $user 
     * @param object $trick 
     * 
     * @Route("/tricks/show/{slug}", name="show_trick")
     *
     * @return response
     */
    public function showTricks(Request $request, UserInterface $user = null, Tricks $trick): Response
    {   
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->commentPagination(1, $trick->getId(), 0, 4);

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre commentaire vient d\'être envoyé et doit être valide pas les administrateurs!');
            $comment->setStatus(0);
            $comment->setUserId($user);
            $comment->setTricksId($trick);
            $comment->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_trick', ['slug' => $trick->getSlug(), '_fragment' => 'commentaire']);
        }

        return $this->render(
            'tricks/show.html.twig',
            [
                'form' => $form->createView(),
                'photos' => $trick->getPhotos(),
                'videos' => $trick->getVideos(),
                'trick' => $trick,
                'comments'=> $comments
            ]
        );
    }
    /**
     * Create trick
     * 
     * @param object $request 
     * @param object $file  
     * 
     * @Route("/auth/tricks/create", name="create_trick")
     *
     * @return response
     */
    public function createTricks(Request $request, File $file): Response
    {
        $trick = new Tricks;

        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre Tricks à été enregistré');
            $images = $form['photos']->getData();
            $file->uploadMultipleImage($images);
            $trick->setSlug((new Slugify())->sluggerLowerCase($trick->getTitle()));
            $trick->setCreateDate(new \DateTime());
            $trick->setUpdateDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
        }
        return $this->render(
            'tricks/create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Update trick
     * 
     * @param object $request 
     * @param string $slug 
     * @param object $trick 
     * @param object $file 
     * 
     * @Route("/auth/tricks/update/{slug}", name="update_trick")
     *
     * @return response 
     */
    public function updateTricks(Request $request, string $slug, Tricks $trick, File $file): Response
    {
        if (!$trick) {
            throw $this->createNotFoundException('Aucun produit trouvé pour '.$slug);
        }
        $oldPhotos = new ArrayCollection();
        foreach ($trick->getPhotos() as $image) {
            $oldPhotos->add($image);
        }
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre Tricks à étais modifier');
            $images =$form['photos']->getData();
            $file->updateMultipleImage($images);
            $trick->setSlug((new Slugify())->sluggerLowerCase($trick->getTitle()));
            $trick->setUpdateDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $file->removeMultipleOldImage($oldPhotos, $trick);
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
        }
        return $this->render(
            'tricks/update.html.twig',
            [
                'photos' => $trick->getPhotos(),
                'trick' => $trick,
                'form' => $form->createView(),
            ]
        );
    }
    /**
     * Remove one trick
     * 
     * @param int    $id 
     * @param object $file 
     * @param object $trick 
     * 
     * @Route("/auth/tricks/delete/{id}", name="remove_trick", requirements={"id"="\d+"})
     *
     * @return response 
     */
    public function removeTricks(int $id, File $file, Tricks $trick): Response
    {
        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy(['tricksId' => $id]);

        $file->removeMultipleImage($photos);
        $this->addFlash('success', 'Votre Tricks à été supprimé');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();
        return $this->redirectToRoute('home_page');
    }
    /**
     * Found tricks
     * 
     * @param object $request 
     * 
     * @Route("/tricks/api/found", name="found_tricks", methods={"POST"})
     * 
     * @return response
     */
    public function foundTricks(Request $request)
    {
        $data = json_decode($request->getContent(), true);
                
        $tricks = $this->getDoctrine()
            ->getRepository(Tricks::class)
            ->tricksPagination($data['trickStart'], $data['maxResult']);

        return $this->render(
            'tricks/moreTrick.html.twig', [
                'tricks'=> $tricks
            ]
        );
    }
    /**
     * Count trick
     * 
     * @Route("/tricks/api/count", name="count_tricks", methods={"GET"})
     * 
     * @return response
     */
    public function countTricks(): JsonResponse
    {
         $trickCount = $this->getDoctrine()
             ->getRepository(Tricks::class)
             ->tricksCount();

        return new JsonResponse(
            [
            'trickCount' => $trickCount
            ]
        );
    }
}
