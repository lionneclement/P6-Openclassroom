<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/all", name="all_comment")
     */
    public function AllComment()
    {        
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render(
            'comment/Comment.html.twig', [
            'comments' => $comments,
            ]
        );
    }

    /**
     * @Route("/comment/invalide", name="invalide_comment")
     */
    public function InvalideComment()
    {
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAllCommentByStatus(0);

        return $this->render(
            'comment/Comment.html.twig', [
            'comments' => $comments,
            ]
        );
    }

    /**
     * @Route("/comment/delete/{id}", name="delete_comment", requirements={"id"="\d+"})
     */
    public function DeleteComment(int $id, Request $request)
    {
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();
        $this->addFlash('success', 'Le commentaire à étais supprimer');
        
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Route("/comment/changeStatus/{id}", name="change_status_comment", requirements={"id"="\d+"})
     */
    public function ChangeStatusComment(int $id, Request $request)
    {
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
    
        $comment->setStatus(!$comment->getStatus());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
        $this->addFlash('success', 'Le commentaire à étais modifer');
        
        return $this->redirect($request->headers->get('referer'));
    }
}
