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
     * @Route("/tricks/show/{id}", name="show_trick", requirements={"id"="\d+"})
     */
    public function showTricks(Request $request, int $id,UserInterface $User=null, Tricks $trick)
    {
        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy(['TricksId'=>$id]);

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(['TricksId'=>$id, 'Status'=>1]);

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre commentaire viens d\'être envoyer et doit être valider pas les administrateurs!');
            $comment->setStatus(0);
            $comment->setUserId($User);
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
     * @Route("/auth/tricks/create", name="create_trick")
     */
    public function createTricks(Request $request, File $File)
    {
        $trick = new Tricks;

        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre Tricks à étais enregistrer');
            $Images =$form['Photos']->getData();
            foreach($Images as $Image){
                $imageFileName = $File->uploadImage($Image);
                $Photo = new Photo;
                $Photo->setName($imageFileName);
                $trick->addPhoto($Photo);
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
     * @Route("/auth/tricks/update/{id}", name="update_trick", requirements={"id"="\d+"})
     */
    public function updateTricks(Request $request,int $id, Tricks $trick, File $File)
    {
        if (!$trick) {
            throw $this->createNotFoundException('No product found for id '.$id);
        }
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre Tricks à étais modifier');
            $Images =$form['Photos']->getData();
            foreach($Images as $Image){
                $imageFileName = $File->uploadImage($Image);
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
     * @Route("/auth/tricks/delete/{id}", name="remove_trick", requirements={"id"="\d+"})
     */
    public function removeTricks($id, File $File, Tricks $trick)
    {
        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findBy(['TricksId'=>$id]);
        foreach($photos as $photo){
            $File->removeImage($photo->getName());
        }
        $this->addFlash('success', 'Votre Tricks à étais supprimer');
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();
        return $this->redirectToRoute('home_page');
    }
}
