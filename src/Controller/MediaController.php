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
     * @Route("/auth/tricks/photo/delete/{id}", name="remove_photo", requirements={"id"="\d+"})
     */
    public function removeOnePhoto(Photo $photo, File $File, Request $request)
    {
        $filename = $photo->getName();
        $File->removeImage($filename);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
