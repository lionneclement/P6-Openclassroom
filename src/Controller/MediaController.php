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
     * @param object $File 
     * @param object $request 
     * 
     * @Route("/auth/tricks/photo/delete/{id}", name="remove_photo", requirements={"id"="\d+"})
     * 
     * @return response
     */
    public function removeOnePhoto(Photo $photo, File $File, Request $request): Response
    {
        $filename = $photo->getName();
        $File->removeImage($filename);

        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
