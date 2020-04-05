<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/tricks/photo/delete/{photoId}", name="remove_photo", requirements={"photoId"="\d+"})
     */
    public function removeOnePhoto($photoId, File $File, Request $request)
    {
        $photo = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->find($photoId);

        $filename = $photo->getName();
        $File->removeImage($filename);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
