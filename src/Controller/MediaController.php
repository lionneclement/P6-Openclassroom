<?php

/** 
 * The file is for media
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

use App\Entity\Photo;
use App\Entity\Video;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** 
 * The file is for media
 * 
 * PHP version 7.3.5
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class MediaController extends AbstractController
{
    /**
     * Remove one photo
     * 
     * @param object $photo 
     * @param object $file 
     * @param object $request 
     * 
     * @Route("/auth/tricks/photo/delete/{id}", name="remove_photo", requirements={"id"="\d+"})
     * 
     * @return response
     */
    public function removeOnePhoto(Photo $photo, File $file, Request $request): Response
    {
        $filename = $photo->getName();
        $file->removeImage($filename);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($photo);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * Remove one video
     * 
     * @param object $video 
     * @param object $request 
     * 
     * @Route("/auth/tricks/video/delete/{id}", name="remove_video", requirements={"id"="\d+"})
     * 
     * @return response
     */
    public function removeOneVideo(Video $video, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($video);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
