<?php
/** 
 * The file is for comment
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/** 
 * The class is for comment
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class CommentController extends AbstractController
{
    /**
     * Render all comment
     * 
     * @Route("/admin/comment/all", name="all_comment")
     * 
     * @return response
     */
    public function allComment()
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
     * Render invalide comment
     * 
     * @Route("/admin/comment/invalide", name="invalide_comment")
     * 
     * @return response
     */
    public function invalideComment()
    {
        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(['status'=>0]);

        return $this->render(
            'comment/Comment.html.twig', [
            'comments' => $comments,
            ]
        );
    }

    /**
     * Delete one comment
     * 
     * @param object $request 
     * @param object $comment 
     * 
     * @Route("/admin/comment/delete/{id}", name="delete_comment", requirements={"id"="\d+"})
     * 
     * @return response
     */
    public function deleteComment(Request $request, Comment $comment)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();
        $this->addFlash('success', 'Le commentaire à étais supprimer');
        
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * Change status comment
     * 
     * @param object $request 
     * @param object $comment 
     * 
     * @Route("/admin/comment/changeStatus/{id}", name="change_status_comment", requirements={"id"="\d+"})
     * 
     * @return response
     */
    public function changeStatusComment(Request $request, Comment $comment)
    {
        $comment->setStatus(!$comment->getStatus());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
        $this->addFlash('success', 'Le commentaire à étais modifer');
        
        return $this->redirect($request->headers->get('referer'));
    }
}
