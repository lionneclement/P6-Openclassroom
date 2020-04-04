<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Photo;
use App\Entity\Tricks;
use App\Form\CommentType;
use App\Form\TricksType;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/show/{id}", name="show_tricks", requirements={"id"="\d+"})
     */
    public function showTricks(Request $request, int $id,UserInterface $User=null)
    {
        $trick = $this->getDoctrine()
        ->getRepository(Tricks::class)
        ->findStyleNameById($id);
        
        $photos = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->findByTricksId($id);

        $comments = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->findAllCommentByTricksId($id, 1);

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Votre message à étais enregistrer');
            $comment->setStatus(0);
            $comment->setUserId($User);
            $comment->setTricksId($trick);
            $comment->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_tricks',['id'=>$id]);
        }

        return $this->render('tricks/show.html.twig', [
            'form' => $form->createView(),
            'photos' => $photos,
            'trick'=> $trick,
            'comments' => $comments,
        ]);
    }
    /**
     * @Route("/tricks/create", name="create_tricks")
     */
    public function createTricks(Request $request, File $File)
    {
        $trick = new Tricks;

        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Votre Tricks à étais enregistrer');
            $Images =$form['Photos']->getData();
            foreach($Images as $Image){
                $imageFileName = $File->uploadImage($Image);
                $Photo = new Photo;
                $Photo->setName($imageFileName);
                $trick->addPhoto($Photo);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();
            
            return $this->redirectToRoute('home_page');
        }
        return $this->render('tricks/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks/update/{id}", name="update_tricks", requirements={"id"="\d+"})
     */
    public function updateTricks(Request $request,int $id, File $File)
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
            $Images =$form['Photos']->getData();
            foreach($Images as $Image){
                $imageFileName = $File->uploadImage($Image);
                $Photo = new Photo;
                $Photo->setName($imageFileName);
                $trick->addPhoto($Photo);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('home_page');
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
        return $this->redirectToRoute('home_page');
    }
}
