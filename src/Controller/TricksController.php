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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param int    $id 
     * @param object $user 
     * @param object $trick 
     * 
     * @Route("/tricks/show/{id}", name="show_trick", requirements={"id"="\d+"})
     *
     * @return response
     */
    public function showTricks(Request $request, int $id,UserInterface $user=null, Tricks $trick): Response
    {
        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy(['tricksId'=>$id]);

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(['tricksId'=>$id, 'status'=>1]);

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre commentaire viens d\'être envoyer et doit être valider pas les administrateurs!');
            $comment->setStatus(0);
            $comment->setUserId($user);
            $comment->setTricksId($trick);
            $comment->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_trick', ['id'=>$id, '_fragment' => 'commentaire']);
        }

        return $this->render(
            'tricks/show.html.twig', [
            'form' => $form->createView(),
            'photos' => $photos,
            'trick'=> $trick,
            'comments' => $comments,
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
            $this->addFlash('success', 'Votre Tricks à étais enregistrer');
            $images =$form['photos']->getData();
            foreach ($images as $image) {
                $imageFileName = $file->uploadImage($image);
                $photo = new Photo;
                $photo->setName($imageFileName);
                $trick->addPhoto($photo);
            }
            $trick->setCreateDate(new \DateTime());
            $trick->setUpdateDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();
            
            return $this->redirectToRoute('home_page');
        }
        return $this->render(
            'tricks/form.html.twig', [
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * Update trick
     * 
     * @param object $request 
     * @param int    $id 
     * @param object $trick 
     * @param object $file 
     * 
     * @Route("/auth/tricks/update/{id}", name="update_trick", requirements={"id"="\d+"})
     *
     * @return response 
     */
    public function updateTricks(Request $request,int $id, Tricks $trick, File $file): Response
    {
        if (!$trick) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre Tricks à étais modifier');
            $Images =$form['photos']->getData();
            foreach ($Images as $Image) {
                $imageFileName = $file->uploadImage($Image);
                $Photo = new Photo;
                $Photo->setName($imageFileName);
                $trick->addPhoto($Photo);
            }
            $trick->setUpdateDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
        }
        return $this->render(
            'tricks/form.html.twig', [
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
            ->findBy(['tricksId'=>$id]);
        foreach ($photos as $photo) {
            $file->removeImage($photo->getName());
        }
        $this->addFlash('success', 'Votre Tricks à étais supprimer');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();
        return $this->redirectToRoute('home_page');
    }
}
