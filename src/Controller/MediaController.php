<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/tricks/photo/delete/{photoId}/{trickId}", name="remove_photo", requirements={"photoId"="\d+","trickId"="\d+" })
     */
    public function removeOnePhoto($photoId, $trickId, File $File)
    {
        $photo = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->find($photoId);

        $filename = $photo->getName();
        $File->removeImage($filename);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirect('/tricks/show/'.$trickId);
    }
    
    public function removeMultiplePhotos($id, $File)
    {
        $photos = $this->getDoctrine()
        ->getRepository(Photo::class)
        ->findByTricksId($id);

       foreach($photos as $photo){
            $File->removeImage($photo->getName());
       }
    }
}
